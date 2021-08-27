<?php

namespace App\Receivers;

use App\Models\Rover;

interface RoverReceiver {
    public function setCommand(string $command): void;
    public function invoke(Rover $rover): void;
}
