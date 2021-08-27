<?php

namespace App\Providers;

use App\Http\Controllers\NasaRoverController;
use App\Http\Controllers\SpaceXRoverController;
use App\Services\AbstractRoverService;
use App\Services\NasaRoverService;
use App\Services\SpaceXRoverService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(NasaRoverController::class)
            ->needs(AbstractRoverService::class)
            ->give(NasaRoverService::class);

        $this->app->when(SpaceXRoverController::class)
            ->needs(AbstractRoverService::class)
            ->give(SpaceXRoverService::class);
    }
}
