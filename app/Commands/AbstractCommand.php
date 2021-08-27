<?php

namespace App\Commands;

use App\Services\AbstractRoverService;
use App\Models\Rover;

abstract class AbstractCommand {
    protected AbstractRoverService $roverService;
    public function __construct(AbstractRoverService $roverService) {
        $this->roverService = $roverService;
    }
    abstract public function execute(Rover $rover): void;
}
