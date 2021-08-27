<?php

namespace App\Commands;

use App\Models\Rover;
use App\Helpers\Direction;

class LeftCommand extends AbstractCommand {
    /**
     * @param Rover $rover
     * @throws \Exception
     */
    public function execute(Rover $rover): void {
        $this->roverService->rotate($rover, Direction::toLeft($rover->direction));
    }
}
