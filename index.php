<?php
use Siervo\Request;
use Siervo\Siervo;
require "src/Siervo/Siervo.php";
Siervo::registerAutoload();
$app = Siervo::getInstance();

$app->notFound(function($req, $resp){
    $resp->header("Content-Type: text/html; charset=UTF-8");
    echo "Ups, la dirección: {$req->getUri()} no existe";
});

$app
    ->uso(function($req, $res, $next){
    echo "Primero voy yo<br>";
    $next();
})
    ->uso(function($req, $res, $next){
    echo "Segundo voy yo<br>";
    $next();
});

/*$app->uso(function($req, $res, $next){
    echo "Segundo voy yo<br>";
    $next();
});*/
$app->get('*', function(){
    echo "soy un comodin";
});
$app->get('', function(){echo "ja!";});
$app->get('/', function(){echo "Hola Mundo!";});
$app->get('/jaba', function(){echo "Hola Jaba!";});
$app->get('/hola/:name',
    [function($req, $resp, $next){echo "Hola1 {$req->param->name}!";$next();}, function($req, $resp){echo "Hola2 {$req->param->name}!";}]);
$app->get('/hola/:name/como/:inter', function($req, $resp){echo "Hola {$req->param->name}, como {$req->param->inter}!";});
$app->route('/hola/:name/como/:inter/:juju/:uno/ok/:mas')
    ->get(function($req, $resp){
        echo "GET: name: {$req->param->name}, inter: {$req->param->inter}, juju: {$req->param->juju}, uno: {$req->param->uno} y mas: {$req->param->mas}";
    })
    ->post(function($req, $resp){
        echo "POST: name: {$req->param->name}, inter: {$req->param->inter}, juju: {$req->param->juju}, uno: {$req->param->uno} y mas: {$req->param->mas}";
    })
    ->put(function($req, $resp){
        echo "PUT: name: {$req->param->name}, inter: {$req->param->inter}, juju: {$req->param->juju}, uno: {$req->param->uno} y mas: {$req->param->mas}";
    })
    ->delete(function($req, $resp){
        echo "DELETE: name: {$req->param->name}, inter: {$req->param->inter}, juju: {$req->param->juju}, uno: {$req->param->uno} y mas: {$req->param->mas}";
    });
$app->post('/post', function($req){
    echo "POST: ";
    var_dump($_POST);
});
$app->put('/put', function($req, $resp){
    echo "PUT: ";
    var_dump($req->body);
});
$app->delete('/delete', function(){echo "DELETE";});
$app->get('/get', function(Request $req){
    echo "GET: ";
    var_dump($_GET);
});

$app->run();
