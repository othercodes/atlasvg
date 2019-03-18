<?php
/** @var \Laravel\Lumen\Routing\Router $router */
$router->get('/[{bid:[0-9]+}]', 'AtlasVGController@index');
$router->get('/app/signin', 'AuthController@signin');
$router->get('/app/callback', 'AuthController@callback');
$router->get('/app/sync', 'SyncController@sync');