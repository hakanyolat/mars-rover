<?php

namespace App\Commands;

use App\Models\Rover;
use App\Helpers\Direction;

class RightCommand extends AbstractCommand {
    /**
     * @param Rover $rover
     * @throws \Exception
     */
    public function execute(Rover $rover): void {
        $this->roverService->rotate($rover, Direction::toRight($rover->direction));
    }
}
