<?php
require "Siervo/Siervo.php";

$app = new \Siervo\Siervo();
$app->get('/jaGET', function(){echo "Hola Mundo!";});
#$app->route('/jojojoGET')->get(function(){return null;})->post(function(){return null;});
#$app->post('/post', function(){return null;});
#$app->put('/put', function(){return null;});
#$app->delete('/delete', function(){return null;});

#$app->test();
$app->run();
