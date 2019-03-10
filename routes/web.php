<?php

$router->get('/', 'AtlasVGController@index');
$router->get('/signin', 'AuthController@signin');
$router->get('/callback', 'AuthController@callback');
$router->get('/sync', 'SyncController@sync');