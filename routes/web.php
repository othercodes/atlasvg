<?php

$router->get('/[{bid}]', 'AtlasVGController@index');
$router->get('/app/signin', 'AuthController@signin');
$router->get('/app/callback', 'AuthController@callback');
$router->get('/app/sync', 'SyncController@sync');