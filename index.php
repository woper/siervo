<?php
require "Siervo/Siervo.php";

$app = new \Siervo\Siervo();
$app->get('/', function(){echo "Hola Mundo!";});
$app->get('/jaba', function(){echo "Hola Jaba!";});
$app->get('/hola/:name', function($name){echo "Hola {$name}!";});
#$app->route('/jojojoGET')->get(function(){return null;})->post(function(){return null;});
#$app->post('/post', function(){return null;});
#$app->put('/put', function(){return null;});
#$app->delete('/delete', function(){return null;});

#$app->test();
$app->run();
