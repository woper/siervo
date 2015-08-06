<?php
use Siervo\Siervo;
require "Siervo/Siervo.php";
Siervo::registerAutoload();
$app = new Siervo();

$app->notFound(function($req, $resp){
    $resp->header("Content-Type: text/html; charset=UTF-8");
    echo "Ups, la dirección: {$req->getUri()} no existe";
});

$app->get('', function(){echo "ja!";});
$app->get('/', function(){echo "Hola Mundo!";});
$app->get('/jaba', function(){echo "Hola Jaba!";});
$app->get('/hola/:name', function($req, $resp){echo "Hola {$req->name}!";});
$app->get('/hola/:name/como/:inter', function($req, $resp){echo "Hola {$req->name}, como {$req->inter}!";});
$app->route('/hola/:name/como/:inter/:juju/:uno/ok/:mas')
    ->get(function($req, $resp){
        echo "GET: name: {$req->name}, inter: {$req->inter}, juju: {$req->juju}, uno: {$req->uno} y mas: {$req->mas}";
    })
    ->post(function($req, $resp){
        echo "POST: name: {$req->name}, inter: {$req->inter}, juju: {$req->juju}, uno: {$req->uno} y mas: {$req->mas}";
    })
    ->put(function($req, $resp){
        echo "PUT: name: {$req->name}, inter: {$req->inter}, juju: {$req->juju}, uno: {$req->uno} y mas: {$req->mas}";
    })
    ->delete(function($req, $resp){
        echo "DELETE: name: {$req->name}, inter: {$req->inter}, juju: {$req->juju}, uno: {$req->uno} y mas: {$req->mas}";
    });
$app->post('/post', function(){echo "POST";});
$app->put('/put', function(){echo "PUT";});
$app->delete('/delete', function(){echo "DELETE";});

$app->run();
