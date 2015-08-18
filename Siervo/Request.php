<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 27/07/15
 * Time: 17:42
 */

namespace Siervo;

use Exception;

class Request {

    /**
     * @var string
     */
    private $uri;

    /**
     * @var string
     */
    private $method;

    /**
     * @var array()
     */
    private $headers;

    /**
     * @var array()
     */
    private $_input;
    
    /**
     * Constructor
     *
     */
    public function __construct(){
        $this->setUri();
        $this->setMethod();
        $this->setHeaders();
        $this->setGlobals();
    }

    private function setGlobals(){
        $this->setInput();
    }

    /**
     * Set Input
     *
     * Convierte en array al flujo de entrada
     * php://input y lo pone a disposición en el
     * array $input de un objeto Request.
     */
    private function setInput(){
        parse_str(file_get_contents("php://input"), $this->_input);
    }

    /**
     * Input
     *
     * Retorna el valor del parámetro pasado,
     * alojado en la propiedad privada $_input
     * que contiene el flujo de entrada si el
     * reuqest method fue PUT.
     *
     * @param $name
     * @return mixed
     */
    public function input($name){
        return (isset($this->_input)) ? $this->_input[$name] : $this->_input;
    }

    /**
     * Get
     *
     * Retorna el valor del parámetro pasado,
     * alojado en la superglobal $_GET.
     *
     * @param $name
     * @return mixed
     */
    public function get($name){
        return $_GET[$name];
    }

    /**
     * Post
     *
     * Retorna el valor del parámetro pasado,
     * alojado en la superglobal $_POST.
     *
     * @param $name
     * @return mixed
     */
    public function post($name){
        return $_POST[$name];
    }

    /**
     * Files
     *
     * Retorna el valor del parámetro pasado,
     * alojado en la superglobal $_FILES.
     *
     * @param $name
     * @return mixed
     */
    public function files($name){
        return $_FILES[$name];
    }
    
    /**
     * Get Method
     *
     * Retorna el método utilizado con el que el cliente
     * realiza la petición al servidor.
     *
     * @return string
     */
    public function getMethod(){
        return $this->method;
    }

    /**
     * Get Headers
     *
     * Retorna un array compuesto de la siguiente
     * forma 'opcion de cabecera' => 'valor'.
     *
     * @return array
     */
    public function getHeaders(){
        return $this->headers;
    }

    /**
     * Get Uri
     *
     * Retorna la uri a la que se le realizo la
     * request.
     *
     * @return string
     */
    public function getUri(){
        return $this->uri;
    }

    /**
     * Get Uri Array
     *
     * Retorna la uri a la que se le realizo la
     * request transformada en un array que
     * contiene sus partes, se crea con
     * explode, utilizando / como delimitador
     * de corte.
     *
     * @return array
     */
    public function getUriArray(){
        return explode('/', $this->uri);
    }

    /**
     * Set Method
     *
     * Setea el método utilizado con el que el cliente
     * realiza la petición al servidor.
     *
     * @throws Exception
     */
    private function setMethod(){
        $this->method = $_SERVER['REQUEST_METHOD'];
        if(($this->method === 'POST') && (array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER))):
            if($_SERVER === 'DELETE'):
                $this->method = 'DELETE';
            elseif($_SERVER['HTTP_X_HTTP_METHOD'] === 'PUT'):
                $this->method = 'PUT';
                $this->setInput();
            else:
                throw new Exception('Unexpected Header');
            endif;
        endif;
    }

    /**
     * Set Uri
     *
     * Setea la uri a la que se realizo la request,
     * teniendo en cuenta el path relativo de siervo
     * para poder trabajar en subdirectorios.
     *
     */
    private function setUri(){
        $this->uri = substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), strlen(Siervo::$_rPATH));
        if($this->uri === false):
            $this->uri = '/';
        elseif(Siervo::$_rPATH == '/'):
            $this->uri = "/{$this->uri}";
        endif;
    }

    /**
     * Set Headers
     *
     * Setea las cabeceras de la request en
     * un array de la siguiente forma
     * 'opcion de cabecera' => 'valor'.
     *
     */
    private function setHeaders(){
        $this->headers = apache_request_headers();
    }

    /**
     * Add Args
     *
     * Agrega a la request los parámetros
     * que pueden venir en la requestUri.
     *
     * @param array $args
     */
    public function addArgs($args = array()){
        if(!empty($args)):
            foreach ($args as $arg => $value):
                $this->$arg = $value;
            endforeach;
        endif;
    }
}