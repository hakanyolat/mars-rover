<?php

namespace App\Services;

use App\Receivers\NasaReceiver;
use App\Receivers\RoverReceiver;

class NasaRoverService extends AbstractRoverService {
    /**
     * @return RoverReceiver
     */
    public function getReceiver(): RoverReceiver {
        return new NasaReceiver($this);
    }
}
