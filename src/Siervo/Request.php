<?php

namespace Siervo;
use Exception;

/**
 * Class Request
 *
 * @author Maxi Nivoli <m_nivoli@hotmail.com>
 * @package Siervo
 */

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
     * @var \stdClass
     */
    public $param;

    /**
     * @var mixed
     */
    public $body;

    /**
     * Constructor
     *
     */
    public function __construct(){
        $this->setUri();
        $this->setMethod();
        $this->setHeaders();
        $this->setBody();
        $this->createParam();
    }

    /**
     * Create Param
     *
     * Crea un objeto del tipo stdClass para
     * alojar como propiedades los parametros
     * que vienen en la uri.
     *
     */
    private function createParam(){
        $this->param = new \stdClass();
    }

    /**
     * Set Body
     *
     * Setea el flujo de entrada
     * php://input y lo pone a disposición
     * en la propiedad body de un objeto Request.
     *
     */
    private function setBody(){
        $this->body = file_get_contents("php://input");
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
     * getHeader
     *
     * Retorna el valor del campo de
     * cabecera request pasado como
     * parametro.
     *
     * @param $field
     * @return mixed | string
     */
    public function getHeader($field){
        return $this->headers[$field];
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
            else:
                throw new Exception('Unexpected Header');
            endif;
        elseif($this->method === "OPTIONS" && (array_key_exists("HTTP_ACCESS_CONTROL_REQUEST_METHOD", $_SERVER))):
            $this->method = $_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"];
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            if (isset($_SERVER['HTTP_ORIGIN'])) {
                header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
                header('Access-Control-Allow-Credentials: true');
                header('Access-Control-Max-Age: 86400');    // cache for 1 day
            }
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

            exit;
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
                $this->param->$arg = $value;
            endforeach;
        endif;
    }
}