<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 23/07/15
 * Time: 18:04
 */

namespace Siervo;

class Router {

    /**
     * @var string ruta actual a la que se le
     * esta asociando comportamiento.
     */
    public $currentRoute;

    /**
     * @var [][] ej.: [requestMethod][route] = callback.
     */
    private $routes;

    /**
     * @var Siervo
     */
    private $app;

    /**
     * Constructor
     *
     * @param Siervo $siervo
     */
    public function __construct(Siervo $siervo){
        $this->app = $siervo;
    }

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
     * @param $requestMethod
     * @return $this|null
     */
    private function addRoute($args, $requestMethod){
        switch(count($args)):
            case 1:
                $this->setArrayRoute($this->currentRoute, $args[0], $requestMethod);
                return $this;
            case 2:
                $this->setArrayRoute($args[0], $args[1], $requestMethod);
                return null;
            default:
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
     * @param $requestMethod
     */
    private function setArrayRoute($key, $callback, $requestMethod){
        $this->routes[$requestMethod][$key] = $callback;
    }

    /**
     * Get Routes
     *
     * Retorna un array que tiene como llava
     * las rutas registradas para un tipo de
     * request especifico.
     *
     * @param $requestMethod
     * @return array()
     */
    public function getRoutes($requestMethod){
        $result = array();
        if(array_key_exists($requestMethod, $this->routes)):
            $result = $this->routes[$requestMethod];
        endif;
        return $result;
    }
}