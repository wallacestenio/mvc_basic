<?php 

namespace App\Controller\Pages;
use \App\Utils\View;
use \App\Model\Entity\Organiaztion;

class About extends Page {
    /**
     * Método responsável por retornar o conteúdo (view) da nossa pagina de Sobre
     * @return string
     */
    public static function getAbout(){
        
        //ORGANIZAÇÃO
        $objectOrganization = new Organiaztion;
        /*echo "<pre>";
        print_r($objectOrganization);
        echo "</pre>"; exit;*/

        //VIEW DA HOME
        $content = View::render('pages/about',[
            'name' => $objectOrganization->name,
            'description' => $objectOrganization->description,
            'site' => 'Site Oficial:' . $objectOrganization->site
        ]);

        //RETORNA A VIEW DA PÁGINA
        return parent::getPage('SOBRE > WStenio TI', $content);
    }

}