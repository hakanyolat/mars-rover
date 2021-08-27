<?php

namespace App\Http\Controllers;

use App\Services\AbstractRoverService;
use App\Services\SpaceXRoverService;

class SpaceXRoverController extends AbstractRoverController {
    protected SpaceXRoverService $roverService;

    /**
     * @param SpaceXRoverService $roverService
     */
    public function __construct(SpaceXRoverService $roverService)
    {
        $this->roverService = $roverService;
    }

    public function getRoverService(): AbstractRoverService {
        return $this->roverService;
    }
}
