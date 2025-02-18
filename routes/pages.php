<?php

use App\Http\Response;
use \App\Controller\Pages;


//rota home
$objectRouter->get('/', [
    function(){
        return new Response(200, Pages\Home::getHome());
    }
]);


//rota sobre
$objectRouter->get('/sobre', [
    function(){
        return new Response(200, Pages\About::getAbout());
    }
]);

//rota dinámica
$objectRouter->get('/page/{idPage}/{action}',[
    function($idPage, $action){
        return new Response(200, 'Página' . $idPage . ' - ' . $action);
    }
]);