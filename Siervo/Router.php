<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 23/07/15
 * Time: 18:04
 */

namespace Siervo;

class Router {

    public $currentRoute;

    private $routes;

    /**
     * Route
     *
     * Registra una ruta para encadenar más de
     * un método de petición (ej: route('/dd')->get(..)->post).
     *
     * @param $route
     * @return $this
     */
    public function route($route){
        $this->currentRoute = $route;
        return $this;
    }

    /**
     * Get
     *
     * Registra una ruta y un comportamiento para esa ruta
     * cuando el request method es GET.
     *
     * @return $this|null|Siervo
     */
    public function get(){
        return $this->addRoute(func_get_args(), 'GET');
    }

    /**
     * Post
     *
     * Registra una ruta y un comportamiento para esa ruta
     * cuando el request method es POST.
     *
     * @return $this|null|Siervo
     */
    public function post(){
        return $this->addRoute(func_get_args(), 'POST');
    }

    /**
     * Put
     *
     * Registra una ruta y un comportamiento para esa ruta
     * cuando el request method es PUT.
     *
     * @return $this|null|Siervo
     */
    public function put(){
        return $this->addRoute(func_get_args(), 'PUT');
    }

    /**
     * Delete
     *
     * Registra una ruta y un comportamiento para esa ruta
     * cuando el request method es DELETE.
     *
     * @return $this|null|Siervo
     */
    public function delete(){
        return $this->addRoute(func_get_args(), 'DELETE');
    }

    /**
     * Add Route
     *
     * Agrega una ruta dependiendo de como se llame.
     *
     * @param $args
     * @param $requestType
     * @return $this|null
     */
    private function addRoute($args, $requestType){
        switch(count($args)):
            case 1:
                $this->setArrayRoute($this->currentRoute, $args[0], $requestType);
                return $this;
            case 2:
                $this->setArrayRoute($args[0], $args[1], $requestType);
                return null;
            case 0:
                return false;
        endswitch;
    }

    /**
     * Set Array Route
     *
     * Registra una ruta y asocia una callback a la
     * misma.
     *
     * @param $key
     * @param $callback
     * @param $requestType
     */
    private function setArrayRoute($key, $callback, $requestType){
        $this->routes[$requestType][$key] = $callback;
    }

    /**
     * Get Routes
     *
     * Retorna un array que tiene como llava
     * las rutas registradas para un tipo de
     * request especifico.
     *
     * @param $requestType
     * @return bool
     */
    public function getRoutes($requestType){
        $result = false;
        if(array_key_exists($requestType, $this->routes)):
            $result = $this->routes[$requestType];
        endif;
        return $result;
    }
}