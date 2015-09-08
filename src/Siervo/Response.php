<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 27/07/15
 * Time: 19:21
 */

namespace Siervo;


class Response {

    /**
     * @var string
     */
    public $body;

    /**
     * Constructor
     *
     * Se le puede pasar de manera opcional el
     * código de estado de la respuesta.
     *
     * @param int $statusCode
     */
    public function __construct($statusCode = 200){
        $this->statusCode($statusCode);
    }

    /**
     * Status Code
     *
     * Indica el código de estado de la respuesta
     * http.
     *
     * @param $statusCode
     */
    public function statusCode($statusCode){
        http_response_code($statusCode);
    }

    /**
     * Header
     *
     * Solo un wrapper para la función header
     * de php.
     *
     * @param $str
     * @param bool $replace
     */
    public function header($str, $replace = true){
        header($str, $replace);
    }

    /**
     * Redirect
     *
     * Redirecciona a la ruta pasada como
     * parámetro, si se quiere redireccionar
     * a otra web, fuera de la app, se debe
     * pasar la url completa (https://www.example.com)
     * y el parámetro outside se debe pasar como true.
     *
     * @param $route
     * @param bool $outside
     */
    public function redirect($route, $outside = false){
        $this->statusCode(302);
        $route = ($outside) ? $route : Siervo::$_rPATH.$route;
        $this->header('Location: '.$route);
    }
}