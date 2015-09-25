<?php

namespace Siervo;

/**
 * Class Response
 *
 * @author Maxi Nivoli <m_nivoli@hotmail.com>
 * @package Siervo
 */

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

    /**
     * JSON
     *
     * Responde JSON, seteando
     * Content-Type: application/json y
     * pasando por json_encode lo que se
     * le pasa por parámetro.
     *
     * @param string $data
     */
    public function json($data = ''){
        $this->header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * Send File
     *
     * Responde con el contenido del
     * archivo cuyo path se pasa como
     * parámetro, la búsqueda de dicho
     * archivo se realiza desde el
     * include path definido en php.
     *
     * @param string $path
     */
    public function sendFile($path = ''){
        echo file_get_contents($path, FILE_USE_INCLUDE_PATH);
    }
}