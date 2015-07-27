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
    private $requestUri;

    /**
     * @var string
     */
    private $requestMethod;

    /**
     * Constructor
     *
     */
    public function __construct(){
        $this->requestUri = substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), strlen(Siervo::$_rPATH));
        $this->setRequestMethod();
    }

    /**
     * Get Request Method
     *
     * retorna el método utilizado con el que el cliente
     * realiza la petición al servidor.
     *
     * @return string
     */
    private function getRequestMethod(){
        return $this->requestMethod;
    }

    /**
     * Set Request Method
     *
     * Setea el método utilizado con el que el cliente
     * realiza la petición al servidor.
     *
     * @throws Exception
     */
    private function setRequestMethod(){
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        if(($$this->requestMethod === 'POST') && (array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER))):
            if($_SERVER === 'DELETE'):
                $this->requestMethod = 'DELETE';
            elseif($_SERVER['HTTP_X_HTTP_METHOD'] === 'PUT'):
                $this->requestMethod = 'PUT';
            else:
                throw new Exception('Unexpected Header');
            endif;
        endif;
    }
}