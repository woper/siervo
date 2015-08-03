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
     */
    public function header($str){
        header($str);
    }
}