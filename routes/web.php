<?php

$router->get('/[{bid}]', 'AtlasVGController@index');
$router->get('/signin', 'AuthController@signin');
$router->get('/callback', 'AuthController@callback');
$router->get('/sync', 'SyncController@sync');