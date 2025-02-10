<?php 

namespace App\Controller\Pages;
use \App\Utils\View;
use \App\Model\Entity\Organiaztion;

class Home extends Page {
    /**
     * Método responsável por retornar o conteúdo (view) da nossa home
     * @return string
     */
    public static function getHome(){
        
        //ORGANIZAÇÃO
        $objectOrganization = new Organiaztion;
        /*echo "<pre>";
        print_r($objectOrganization);
        echo "</pre>"; exit;*/

        //VIEW DA HOME
        $content = View::render('pages/home',[
            'name' => $objectOrganization->name,
            'description' => $objectOrganization->description,
            'site' => 'Site Oficial:' . $objectOrganization->site
        ]);

        //RETORNA A VIEW DA PÁGINA
        return parent::getPage('WDev  - Canal - HOME', $content);
    }

}