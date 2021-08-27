<?php

namespace App\Services;

use App\Models\Plateau;
use App\Repositories\PlateauRepository;
use App\Rules\PlateauHeightRule;
use App\Rules\PlateauWidthRule;
use Illuminate\Contracts\Validation\Validator as ValidatorInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PlateauService {
    private PlateauRepository $plateauRepository;

    /**
     * @param PlateauRepository $plateauRepository
     */
    public function __construct(PlateauRepository $plateauRepository)
    {
        $this->plateauRepository = $plateauRepository;
    }

    /**
     * @param array $data
     * @return ValidatorInterface
     */
    private static function getValidator(array $data): ValidatorInterface {
        return Validator::make($data, [
            'x' => ['required', 'integer', new PlateauHeightRule()],
            'y' => ['required', 'integer', new PlateauWidthRule()],
        ]);
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    private static function validate(array $data): void {
        $validator = self::getValidator($data);
        if ($validator->fails()) {
            throw new BadRequestException($validator->errors()->first());
        }
    }

    /**
     * @param int $id
     * @param int $x
     * @param int $y
     * @return bool
     * @throws \Exception
     */
    public function positionIsAvailable(int $id, int $x, int $y): bool {
        $validator = self::getValidator(['x' => $x, 'y' => $y]);
        if ($validator->fails())
            return false;

        $plateau = $this->get($id);
        if ($x > $plateau->width || $y > $plateau->height)
            return false;

        foreach($plateau->rovers as $rover) {
            if($rover->x == $x && $rover->y == $y)
                return false;
        }

        return true;
    }

    /**
     * @param array $data
     * @return Plateau
     * @throws \Exception
     */
    public function create(array $data): Plateau {
        self::validate($data);
        return $this->plateauRepository->create($data);
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->plateauRepository->findAll();
    }

    /**
     * @param int $id
     * @return Plateau|null
     * @throws \Exception
     */
    public function get(int $id): ?Plateau {
        $plateau = $this->plateauRepository->find($id);
        if (!$plateau) throw new NotFoundHttpException('Plateau not found.');
        return $plateau;
    }

    /**
     * @param int $id
     * @param array $data
     * @return Plateau|null
     * @throws \Exception
     */
    public function update(int $id, array $data): ?Plateau {
        self::validate($data);
        $plateau = $this->plateauRepository->find($id);
        if (!$plateau) throw new NotFoundHttpException('Plateau not found.');
        return $this->plateauRepository->update($plateau, $data);
    }

    /**
     * @param int $id
     * @throws \Exception
     */
    public function delete(int $id)
    {
        $plateau = $this->plateauRepository->find($id);
        if (!$plateau) throw new NotFoundHttpException('Plateau not found.');
        $this->plateauRepository->delete($plateau);
    }
}
