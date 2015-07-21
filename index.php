<?php
require "Siervo/Siervo.php";

$app = new \Siervo\Siervo();

$app->get('/', function(){echo "Hola Mundo!";});
$app->get('/jaba', function(){echo "Hola Jaba!";});
$app->get('/hola/:name', function($name){echo "Hola {$name}!";});
$app->get('/hola/:name/como/:inter', function($name, $inter){echo "Hola {$name}, como {$inter}!";});
$app->route('/hola/:name/como/:inter/:juju/:uno/ok/:mas')
    ->get(function($name, $inter, $juju, $uno, $mas){
        echo "GET: name: {$name}, inter: {$inter}, juju: {$juju}, uno: {$uno} y mas: {$mas}";
    })
    ->post(function($name, $inter, $juju, $uno, $mas){
        echo "POST: name: {$name}, inter: {$inter}, juju: {$juju}, uno: {$uno} y mas: {$mas}";
    })
    ->put(function($name, $inter, $juju, $uno, $mas){
        echo "PUT: name: {$name}, inter: {$inter}, juju: {$juju}, uno: {$uno} y mas: {$mas}";
    })
    ->delete(function($name, $inter, $juju, $uno, $mas){
        echo "DELETE: name: {$name}, inter: {$inter}, juju: {$juju}, uno: {$uno} y mas: {$mas}";
    });
$app->post('/post', function(){echo "POST";});
$app->put('/put', function(){echo "PUT";});
$app->delete('/delete', function(){echo "DELETE";});

#$app->test();
$app->run();
