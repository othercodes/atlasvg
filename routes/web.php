<?php

$router->get('/', 'AtlasVGController@index');
$router->get('/demo', 'AtlasVGController@demo');

$router->get('/buildings', 'BuildingController@index');
$router->post('/buildings', 'BuildingController@index');
$router->put('/buildings/{bid}', 'BuildingController@index');
$router->delete('/buildings/{bid}', 'BuildingController@index');