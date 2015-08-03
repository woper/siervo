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
     * Constructor
     *
     */
    public function __construct(){
        $this->setUri();
        $this->setMethod();
        $this->setHeaders();
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
}