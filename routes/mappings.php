<?php

use \Laravel\Lumen\Routing\Router;

function mapPlateauEndpoints(Router $router) {
    $router->get('/plateaus', 'PlateauController@index');
    $router->post('/plateaus', 'PlateauController@store');
    $router->get('/plateaus/{id}', 'PlateauController@show');
    $router->put('/plateaus/{id}', 'PlateauController@update');
    $router->delete('/plateaus/{id}', 'PlateauController@destroy');
}

function mapRoverV1Endpoints(Router $router) {
    $router->get('/rovers', 'NasaRoverController@index');
    $router->post('/rovers', 'NasaRoverController@store');
    $router->get('/rovers/{id}', 'NasaRoverController@show');
    $router->get('/rovers/{id}/state', 'NasaRoverController@state');
    $router->put('/rovers/{id}', 'NasaRoverController@update');
    $router->post('/rovers/{id}/execute', 'NasaRoverController@execute');
    $router->post('/rovers/{id}/stop', 'NasaRoverController@stop');
    $router->delete('/rovers/{id}', 'NasaRoverController@destroy');
}

function mapRoverV2Endpoints(Router $router) {
    $router->get('/rovers', 'NasaRoverController@index');
    $router->post('/rovers', 'NasaRoverController@store');
    $router->get('/rovers/{id}', 'NasaRoverController@show');
    $router->get('/rovers/{id}/state', 'NasaRoverController@state');
    $router->put('/rovers/{id}', 'NasaRoverController@update');
    $router->post('/rovers/{id}/execute', 'SpaceXRoverController@execute');
    $router->post('/rovers/{id}/stop', 'SpaceXRoverController@stop');
    $router->delete('/rovers/{id}', 'NasaRoverController@destroy');
}
