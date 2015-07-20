<?php
require "Siervo/Siervo.php";

$app = new \Siervo\Siervo();
$app->get('/jaGET', function(){return null;});
$app->route('/jojojoGET')->get(function(){return null;})->post(function(){return null;});
$app->post('/post', function(){return null;});
$app->put('/put', function(){return null;});
$app->delete('/delete', function(){return null;});

$app->test();
$app->run();

echo "Hola Mundo";
