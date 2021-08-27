<?php

namespace App\Receivers;

use App\Commands\AbstractCommand;
use App\Commands\LeftCommand;
use App\Commands\RightCommand;
use App\Commands\MoveCommand;

use App\Services\AbstractRoverService;
use App\Models\Rover;

class NasaReceiver implements RoverReceiver {
    const commands = [
		'L' => LeftCommand::class,
		'R' => RightCommand::class,
		'M' => MoveCommand::class,
	];

	protected Rover $rover;
	protected AbstractRoverService $roverService;
	protected AbstractCommand $command;

    /**
     * @param AbstractRoverService $roverService
     */
	public function __construct(AbstractRoverService $roverService) {
		$this->roverService = $roverService;
	}

    /**
     * @param string $command
     * @throws \Exception
     */
    public function setCommand(string $command): void {
        $commands = array_keys(self::commands);
        if (in_array($command, $commands)) {
            $this->command = new (self::commands[$command])($this->roverService);
        }else{
            throw new \Exception("Invalid command '{$command}'.");
        }
    }

    /**
     * @param Rover $rover
     */
    public function invoke(Rover $rover): void {
        if (isset($this->command)) {
            $this->rover = $rover;
            $this->command->execute($rover);
        }
    }

}
