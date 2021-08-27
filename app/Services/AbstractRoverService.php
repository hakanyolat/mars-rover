<?php

namespace App\Services;

use App\Models\Rover;
use App\Receivers\RoverReceiver;
use App\Repositories\RoverRepository;
use App\Rules\RoverDirectionRule;
use Illuminate\Contracts\Validation\Validator as ValidatorInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class AbstractRoverService
{
    private PlateauService $plateauService;
    protected RoverRepository $roverRepository;

    /**
     * @param RoverRepository $roverRepository
     * @param PlateauService $plateauService
     */
    public function __construct(RoverRepository $roverRepository, PlateauService $plateauService)
    {
        $this->roverRepository = $roverRepository;
        $this->plateauService = $plateauService;
    }

    /**
     * @param array $data
     * @return ValidatorInterface
     */
    private static function getValidator(array $data): ValidatorInterface
    {
        return Validator::make($data, [
            'x' => ['required', 'integer'],
            'y' => ['required', 'integer'],
            'direction' => ['required', new RoverDirectionRule],
            'plateau_id' => ['required', 'integer'],
        ]);
    }

    /**
     * @param ValidatorInterface $validator
     * @throws \Exception
     */
    protected static function validate(ValidatorInterface $validator): void
    {
        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
    }

    /**
     * @param Rover $rover
     * @param int $plateau_id
     * @param int $x
     * @param int $y
     * @return bool
     */
    public function canMove(Rover $rover, int $plateau_id, int $x, int $y): bool {
        if ($rover->x != $x || $rover->y != $y || $rover->plateau_id != $plateau_id) {
            return $this->plateauService->positionIsAvailable($plateau_id, $x, $y);
        }
        return true;
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->roverRepository->findAll();
    }

    /**
     * @param int $id
     * @return Rover|null
     * @throws \Exception
     */
    public function get(int $id): ?Rover
    {
        $rover = $this->roverRepository->find($id);
        if (!$rover) throw new NotFoundHttpException('Rover not found.');
        return $rover;
    }

    /**
     * @param int $id
     * @throws \Exception
     */
    public function delete(int $id)
    {
        $rover = $this->roverRepository->find($id);
        if (!$rover) throw new NotFoundHttpException('Rover not found.');
        $this->roverRepository->delete($rover);
    }

    /**
     * @param array $data
     * @return Rover|null
     * @throws \Exception
     */
    public function create(array $data): ?Rover
    {
        self::validate(self::getValidator($data));

        if (!$this->plateauService->positionIsAvailable($data['plateau_id'], $data['x'], $data['y'])) {
            throw new BadRequestException('The rover can not be deploy here.');
        }

        return $this->roverRepository->create($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Rover|null
     * @throws \Exception
     */
    public function update(int $id, array $data): ?Rover
    {
        self::validate(self::getValidator($data));
        $rover = $this->roverRepository->find($id);

        if (!$rover)
            throw new NotFoundHttpException('Rover not found.');

        if ($rover->state == Rover::Interrupted)
            throw new BadRequestException('Rover is not ready. Please cancel commands in queue first.');

        if (!$this->canMove($rover, $data['plateau_id'], $data['x'], $data['y']))
            throw new BadRequestException('The rover can not be go here.');

        return $this->roverRepository->update($rover, $data);
    }

    /**
     * @param int $id
     * @return Rover|null
     * @throws \Exception
     */
    public function stop(int $id): ?Rover {
        $rover = $this->roverRepository->find($id);
        if (!$rover)
            throw new NotFoundHttpException('Rover not found.');

        return $this->roverRepository->stop($rover);
    }

    /**
     * @param Rover $rover
     * @param int $x
     * @param int $y
     * @return Rover|null
     * @throws \Exception
     */
    public function move(Rover $rover, int $x, int $y): ?Rover
    {
        if (!$this->canMove($rover, $rover->plateau_id, $x, $y)) {
            throw new BadRequestException('The rover can not be go here.');
        }

        return $this->roverRepository->move($rover, $x, $y);
    }

    /**
     * @param Rover $rover
     * @param string $direction
     * @return Rover|null
     */
    public function rotate(Rover $rover, string $direction): ?Rover
    {
        return $this->roverRepository->rotate($rover, $direction);
    }

    /**
     * @param $data
     * @return ValidatorInterface
     */
    private static function getCommandsValidator($data): ValidatorInterface {
        return Validator::make($data, [
            'commands' => ['required', 'string'],
        ]);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Rover|null
     * @throws \Exception
     */
    public function executeCommands(int $id, array $data): ?Rover {
        $this->validate(self::getCommandsValidator($data));
        $rover = $this->get($id);

        if ($rover->state == Rover::Interrupted)
            throw new BadRequestException('Rover is not ready. Please cancel commands in queue first.');

        $index = 0;
        $receiver = $this->getReceiver();
        foreach(str_split($data['commands']) as $command) {
            try {
                $receiver->setCommand($command);
                $receiver->invoke($rover);
            } catch (\Exception $e) {
                return $this->roverRepository->interrupt($rover, substr($data['commands'], $index));
            }
            $index++;
        }

        return $rover;
    }

    abstract public function getReceiver(): RoverReceiver;

}
