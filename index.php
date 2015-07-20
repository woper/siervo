<?php

ini_set('error_reporting', E_ALL | E_STRICT | E_NOTICE);
                ini_set('display_errors', 'On');
                ini_set('track_errors', 'On');

require "Siervo/Siervo.php";

$app = new \Siervo\Siervo();
$app->get('/jaGET', function(){return null;});
$app->route('/jojojoGET')->get(function(){return null;});
$app->post('/post', function(){return null;});
$app->put('/put', function(){return null;});
$app->delete('/delete', function(){return null;});

$app->test();

echo "Hola Mundo";
