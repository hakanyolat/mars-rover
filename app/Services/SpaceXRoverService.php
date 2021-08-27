<?php

namespace App\Services;

use App\Receivers\RoverReceiver;
use App\Receivers\SpaceXReceiver;

class SpaceXRoverService extends AbstractRoverService {
    /**
     * @return RoverReceiver
     */
    public function getReceiver(): RoverReceiver {
        return new SpaceXReceiver($this);
    }
}
