<?php

/** @var Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use \Laravel\Lumen\Routing\Router;

include_once 'mappings.php';

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    mapPlateauEndpoints($router);
    mapRoverV1Endpoints($router);
});
$router->group(['prefix' => 'api/v2'], function () use ($router) {
    mapPlateauEndpoints($router);
    mapRoverV2Endpoints($router);
});

