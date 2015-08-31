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
        $this->routes = array();
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

    /**
     * Process
     *
     * Procesa la request pasada por la app, y
     * retorna dependiendo del comportamiento
     * asociado a la route.
     *
     * @param Request $request
     * @return mixed
     */
    public function process(Request $request){
        return $this->find($request, $this->getRoutes($request->getMethod()));
    }

    /**
     * Find
     *
     * Busca en las rutas registradas la que corresponde
     * con la requestUri, luego le indica a la app si se
     * encontro o no para que esta ejecute el comportamiento
     * asociado, retorna de acuerdo al comportamiento
     * asociado.
     *
     * @param Request $request
     * @param array $routes
     * @return mixed
     */
    public function find(Request $request, $routes = array()){
        $args = array();
        $notFound = true;
        $callback = null;
        $requestUriArray = $request->getUriArray();
        foreach ($routes as $route => $callback):
            $route = explode('/', $route);
            $args = array();
            $notFound = true;
            if(count($requestUriArray) === count($route)):
                $args = $this->match($requestUriArray, $route);
                if($args !== null):
                    $notFound = false;
                    break;
                endif;
            endif;
        endforeach;
        if($notFound):
            $callback = (array_key_exists('*', $routes))
                ? $routes['*'] :
                $this->app->notFoundCallback;
            return $this->app->dispatch($callback);
        else:
            $request->addArgs($args);
            return $this->app->dispatch($callback);
        endif;
    }

    /**
     * Match
     *
     * Se encarga de comprobar que una ruta registrada
     * machee de manera correcta con la requestUri
     * teniendo en cuenta sus parámetros.
     *
     * @param $requestUriArray
     * @param $route
     * @return array|null
     */
    public function match($requestUriArray, $route){
        $args = array();
        foreach($requestUriArray as $i => $part):
            if(substr($route[$i], 0, 1) === ':'):
                $args[ltrim($route[$i], ':')] = $part;
            elseif($part !== $route[$i]):
                $args = null;
                break;
            endif;
        endforeach;
        return $args;
    }
}