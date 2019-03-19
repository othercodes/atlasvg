<?php
/** @var \Laravel\Lumen\Routing\Router $router */
$router->get('/[{bid:[0-9]+}]', 'AtlasVGController@index');
# sync user data for a building
$router->get('/{bid:[0-9]+}/sync', 'SyncController@sync');

# auth in Graph
$router->get('/app/signin', 'AuthController@signin');
# callback route for Graph API
$router->get('/app/callback', 'AuthController@callback');