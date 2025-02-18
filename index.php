<?php

require __DIR__ . '/vendor/autoload.php';


use \App\Http\Router;
use \App\Utils\View;

define('URL', 'http://localhost/NEW_MVC');

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