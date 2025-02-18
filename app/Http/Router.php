<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;

class Router {

    /**
     * URL completa do projeto (raiz)
     * @var string
     */
    private $url = '';
    
    /**
     * Prefixo de todas as rotas
     * @var string
     */
    private $prefix = '';
    
    /**
     * Índice de rotas
     * @var array
     */
    private $routes = [];
    
    /**
     * Instância de Request
     * @var Request;
     */
    private $request;

    /**
     * Método responsável por iniciar a classe
     * @param string $url
     */
    public function __construct($url) {
        $this->request = new Request();
        $this->url     = $url;
        $this->setPrefix();
    }

    /**
     * Método responsavel por definir o prefixo das rotas
     * 
     */
    private function setPrefix(){
        //Informações da URL atual
        $parseUrl = parse_url($this->url);

        //Define o Prefixo
        $this->prefix = $parseUrl['path'] ?? '';

        /*echo "<pre>";
        print_r($parseUrl);
        echo "</pre>";
        exit;*/
    }

    /**
     * Médodo responsável por adicionar uma rota na classe
     * @param string $method
     * @param string $route
     * @param array $params
     * 
     */
    private function addRoute($method, $route, $params = []){
        
        
        //Validação dos parametros
        foreach($params as $key=>$value){
            if($value instanceof Closure){
                $params['controller'] = $value;
                unset($params[$key]);
                continue;

            }
        }

        //variáveis da rota
        $params['variables'] = [];

        //padrão de validação das variáveis das rotas
        $patternVariable = '/{(.*?)}/';
        if(preg_match_all($patternVariable,$route, $matches)){

            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];

            /*
        echo "<pre>";
        print_r($matches);
        echo "</pre>";
        */
        }

        //Padrão de validação da URL
        $patternRoute = '/^' . str_replace('/', '\/', $route). '$/';
        /*echo "<pre>";
        print_r($patternRoute);
        echo "</pre>";*/
        

        //Adiciona a rota dentro da classe

        $this->routes[$patternRoute][$method] = $params;
        
       /* echo "<pre>";
        print_r($this);
        echo "</pre>"; exit;

        echo "<pre>";
        print_r($patternRoute);
        echo "</pre>"; exit;

        echo "<pre>";
        print_r($params);
        echo "</pre>"; exit;*/
    }

    /**
     * Método responsável por definir uma rota de GET
     * @param string $route
     * @param array $params
     * 
     */
    public function get($route, $params = []){
       return $this->addRoute('GET', $route, $params);
    }

    /**
     * Método responsável por definir uma rota de POST
     * @param string $route
     * @param array $params
     * 
     */
    public function post($route, $params = []){
        return $this->addRoute('POST', $route, $params);
     }

     /**
     * Método responsável por definir uma rota de PUT
     * @param string $route
     * @param array $params
     * 
     */
    public function put($route, $params = []){
        return $this->addRoute('PUT', $route, $params);
     }


     /**
     * Método responsável por definir uma rota de DELETE
     * @param string $route
     * @param array $params
     * 
     */
    public function delete($route, $params = []){
        return $this->addRoute('DELETE', $route, $params);
     }

     

    /**
     * Método responsável por retornar a URI desconsiderando o prefixo
     * @return string
     */
    private function getUri(){
        //URI da request
        $uri = $this->request->getUri();

        //Fatia a URI com o prefixo
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

        //retorna a URI sem prefixo
        return end($xUri);
    }

    /**
     * Método responsável por retornar os dados da rota atual
     * @return array
     */
    private function getRoute(){
        //URL
        $uri = $this->getUri();

        //Metodo pegando qual tipo de método (get,post, etc..)
        $httpMethod = $this->request->getHttpMethod();

        //valida as rotas
        foreach($this->routes as $patternRoute=>$methods){
        
            //verifica se a URI bate o padrão
        if(preg_match($patternRoute, $uri, $matches)){
            //verifica o método posição
            if(isset($methods[$httpMethod])){

                //remove a primeira posição.
                unset($matches[0]);

                //Variaveis processadas
                $keys = $methods[$httpMethod]['variables'];
                $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                $methods[$httpMethod]['variables']['request'] = $this->request;
                /*
                echo "<pre>";
                print_r($methods);
                echo "</pre>"; exit;
                              
                echo "<pre>";
                print_r($methods[$httpMethod]['variables']);
                echo "</pre>"; exit;

                echo "<pre>";
                print_r($matches);
                echo "</pre>"; exit;
                
                */

                    //retorno dos parâmetros da rota
                    return $methods[$httpMethod];
                }
                
                //Método não permitido
                throw new Exception("Método não é permitido", 405);
            }
        }
        
        //URL não encontrada
        throw new Exception("URL não encontrada", 404);
    }

    /**
     * Método responsável por executar a rota atual
     * @return Response
     */
    public function run(){

        try{

            
            //Obtém a rota atual
            $route = $this->getRoute();

             /* echo "<pre>";
                print_r($route);
                echo "</pre>"; exit;
             */


            //verifica o controlador
            if(!isset($route['controller'])){
                throw new Exception('A URL não pode ser processada', 500);
            }

            //argumentos da função
            $args = [];

            //reflection 
            $reflection = new ReflectionFunction($route['controller']);
            foreach($reflection->getParameters() as $parameter){
               
                $name = $parameter->getName();
                
                $args[$name] = $route['variables'][$name] ?? '';
            }
/*
            echo "<pre>";
            print_r($args);
            echo "</pre>"; exit;
*/

            //retorna a execução da função
            return call_user_func_array($route['controller'], $args);
        
        }catch(Exception $e){
            return new Response($e->getCode(), $e->getMessage());
        }

    }
}