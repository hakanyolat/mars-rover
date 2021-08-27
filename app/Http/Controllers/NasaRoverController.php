<?php

namespace App\Http\Controllers;

use App\Services\AbstractRoverService;
use App\Services\NasaRoverService;

class NasaRoverController extends AbstractRoverController {
    public NasaRoverService $roverService;

    /**
     * @param NasaRoverService $roverService
     */
    public function __construct(NasaRoverService $roverService){
        $this->roverService = $roverService;
    }

    public function getRoverService(): AbstractRoverService
    {
        return $this->roverService;
    }
}
