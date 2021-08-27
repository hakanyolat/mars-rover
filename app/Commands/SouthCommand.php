<?php

namespace App\Commands;

use App\Helpers\Direction;
use App\Helpers\Position;
use App\Models\Rover;

class SouthCommand extends AbstractCommand {
    /**
     * @param Rover $rover
     * @throws \Exception
     */
    public function execute(Rover $rover): void {
        $current = new Position($rover->x, $rover->y);
        $new = $current->getForward(Direction::South);
        $this->roverService->move($rover, $new->x, $new->y);
    }
}
