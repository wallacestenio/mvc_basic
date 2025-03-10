<?php

require __DIR__ . '/vendor/autoload.php';


use \App\Http\Router;
use \App\Utils\View;
use \WallaceStenio\DotEnv\Environment;

//Carrega variaveis de ambiente
Environment::load(__DIR__);


/*echo "<pre>";
print_r(getenv('URL'));
echo "</pre>";
exit();*/

define('URL', 'http://localhost:8080');

//Define o valor padrão das variáveis
View::init([
    'URL' => URL
]);

//inclui as rotas de páginas
$objectRouter = new Router(URL);

//inclui as rotas de páginas
include __DIR__ . '/routes/pages.php';

//imprime o response da rota
$objectRouter->run()->sendResponse();


/*** 
echo "<pre>";
print_r($objectRouter);
echo "</pre>";

exit;
*/
/*$objectRequest = new \App\Http\Request;
echo "<pre>";
print_r($objectRequest);
echo "</pre>";
exit;*/
/*
$objectResponse = new \App\Http\Response(200, 'Olá Mundo');

$objectResponse->sendResponse(); 
*/

/*
echo "<pre>";
print_r($objectResponse);
echo "</pre>";
exit;*/

//echo Home::getHome();