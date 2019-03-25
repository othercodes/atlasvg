<?php
/** @var \Laravel\Lumen\Routing\Router $router */
$router->get('/[{bid:[0-9]+}]', 'AtlasVGController@index');

# auth in Graph
$router->get('/app/signin[/{bid:[0-9]+}]', 'AuthController@signin');
# callback route for Graph API
$router->get('/app/callback[/{bid:[0-9]+}]', 'AuthController@callback');

# sync user data for a building
$router->get('/app/sync[/{bid:[0-9]+}]', 'SyncController@sync');