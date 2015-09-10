<?php

/**
 * Siervo
 *
 * User: max
 * Date: 19/07/2015
 * Time: 20:30
 */

namespace Siervo;


class Siervo{

    /**
     * @var Siervo
     */
    private static $instance;

    /**
     * @var string path absoluto donde se encuentra Siervo.
     */
    public static $_PATH;

    /**
     * @var string nombre del entorno seteado.
     */
    public static $_ENV;

    /**
     * @var string string path relativo donde se ecuentra Siervo.
     */
    public static $_rPATH;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var callback|boolean
     */
    public $notFoundCallback;

    /**
     * @var array() Contiene los distintos
     * entornos registrados (key) y su
     * comportamiento asociado mediante callback (value).
     */
    private $environments;

    /**
     * @var array() Contiene las callbacks,
     * funciones anónimas.
     */
    private $callbackStack;

    /**
     * Get Instance
     *
     * Retorna una instancia de
     * Siervo.
     *
     * @return Siervo
     */
    public static function getInstance(){
        if(!isset(self::$instance)){
            $clase = __CLASS__;
            self::$instance = new $clase;
        }
        return self::$instance;
    }

    /**
     * __clone
     *
     * Para que no se puedan crear nuevos
     * objetos por medio de la clonación.
     *
     * @return null
     */
    private function __clone(){
        return null;
    }

    /**
     * Constructor
     *
     */
    private function __construct(){
        $this->setDefaultEnv();
        $this->setEnv();
        $this->setPath();
        $this->setRPath();
        $this->router = new Router($this);
        $this->callbackStack = array();
    }

    /**
     * Run
     *
     * Corre la app.
     *
     */
    public function run(){
        $this->request = new Request();
        return $this->router->process($this->request);
    }

    /**
     * Set Path
     *
     * Setea el path absoluto donde se encuentra Siervo,
     * y desde ahí setea la raiz de importación.
     *
     * @param string $path
     */
    public function setPath($path = ''){
        self::$_PATH = ($path === '') ? dirname(dirname(__FILE__)) : $path;
        ini_set('include_path', self::$_PATH);
    }

    /**
     * Set R Path
     *
     * Setea el path relativo donde se ejecuta el
     * srcipt de inicio.
     *
     * @param string $rpath
     */
    public function setRPath($rpath = ''){
        self::$_rPATH = ($rpath === '') ? dirname($_SERVER['SCRIPT_NAME']) : $rpath;
    }

    /**
     * Set Env
     *
     * Setea el tipo de entorno de desarrollo.
     *
     * @param string $env
     * @return bool
     */
    public function setEnv($env = 'development'){
        $ok = array_key_exists($env, $this->environments);
        if($ok):
            $callback = $this->environments[$env];
            $ok = is_callable($callback);
            if($ok):
                $callback();
                self::$_ENV = $env;
            endif;
        endif;
        return $ok;
    }

    /**
     * Environment
     *
     * Define un entorno de desarrollo y asocia un
     * compoortamiento mediante una callback al mismo.
     *
     * @param string $env
     * @param null $callback
     */
    public function environment($env = '', $callback = null){
        $this->environments[$env] = $callback;
    }

    /**
     * Set Default Env
     *
     * Setea por defecto dos entornos de desarrollo
     * basicos, development y production, los mismos
     * se pueden reescribir.
     *
     */
    private function setDefaultEnv(){
        $this->environment('development', function(){
            ini_set('error_reporting', E_ALL | E_STRICT | E_NOTICE);
            ini_set('display_errors', 'On');
            ini_set('track_errors', 'On');
        });
        $this->environment('production', function(){
            ini_set('display_errors', 'Off');
        });
    }

    /**
     * Route
     *
     * Le comunica al objeto Router que registre una ruta
     * para encadenar más de un método de petición
     * (ej: route('/dd')->get(..)->post).
     *
     * @param $route
     * @return $this
     */
	public function route($route){		
		return $this->router->route($route);
	}

    /**
     * Get
     *
     * Le comunica al objeto Router que registre
     * una ruta y un comportamiento para esa ruta
     * cuando el request method es GET.
     *
     * @return boolean|Router
     */
	public function get(){
		return (count(func_get_args()) === 1) ? false : call_user_func_array(array($this->router, 'get'), func_get_args());
	}

    /**
     * Post
     *
     * Le comunica al objeto Router que registre
     * una ruta y un comportamiento para esa ruta
     * cuando el request method es POST.
     *
     * @return boolean|Router
     */
	public function post(){
		return (count(func_get_args()) === 1) ? false : call_user_func_array(array($this->router, 'post'), func_get_args());
	}

    /**
     * Put
     *
     * Le comunica al objeto Router que registre
     * una ruta y un comportamiento para esa ruta
     * cuando el request method es PUT.
     *
     * @return boolean|Router
     */
	public function put(){
		return (count(func_get_args()) === 1) ? false : call_user_func_array(array($this->router, 'put'), func_get_args());
	}

    /**
     * Delete
     *
     * Le comunica al objeto Router que registre
     * una ruta y un comportamiento para esa ruta
     * cuando el request method es DELETE.
     *
     * @return boolean|Router
     */
	public function delete(){
		return (count(func_get_args()) === 1) ? false : call_user_func_array(array($this->router, 'delete'), func_get_args());
	}

    /**
     * Register Autoload
     *
     * Registra la función de autoload de siervo,
     * si se siguen ciertos lineamientos también
     * puede servir para importar de forma automática
     * archivos creados por el usuario.
     *
     */
    public static function registerAutoload(){
        spl_autoload_register(function($class){
            $fileDir = dirname(__DIR__)."/".str_replace('\\', '/', $class).'.php';
            if($fileDir):
                require $fileDir;
            endif;
        });
    }

    /**
     * Not Found
     *
     * Registra el comportamiento para cuando no
     * se encuentra la ruta requerida.
     *
     * @param $callback
     */
    public function notFound($callback){
        is_callable($callback) ? $this->notFoundCallback = $callback : $this->notFoundCallback = false;
    }

    /**
     * Dispatch
     *
     * Ejecuta la función anónima
     * pasandole los objetos Request, Response y
     * la callback next() para darle contexto.
     *
     * @param null $callback
     * @return mixed
     */
    public function dispatch($callback = null){
        $this->setCallbackStack($callback);
        $callback = array_shift($this->callbackStack);
        if(is_callable($callback)):
            $this->response = new Response();
            if($callback === $this->notFoundCallback):
                $this->response->statusCode(404);
            endif;
            return $callback($this->request, $this->response, $this->next());
        else:
            throw new \RuntimeException();
        endif;
    }

    /**
     * Set Callback Stack
     *
     * Setea la pila de callbacks a ejecutar.
     *
     * @param $callback
     */
    private function setCallbackStack($callback){
        if(isset($callback)){
            if(is_array($callback)):
                $this->callbackStack = array_merge($this->callbackStack, $callback);
            else:
                $this->callbackStack[] = $callback;
            endif;
        }
    }

    /**
     * Next
     *
     * Retorna una callback que al ejecutarla,
     * se ejecuta la próxima callback registrada.
     *
     * @return callable
     */
    private function next(){
        return function(){
            return $this->dispatch();
        };
    }

    /**
     * Uso
     *
     * Actua como capa middleware,
     * agregando funciones anonimas en
     * callbackStack, para que cuando la
     * ruta haga match, antes de ejecutarse
     * el comportamiento asociado, se ejecute
     * toodo lo que esta en callbackStack.
     *
     * @param $callback
     * @return $this
     */
    public function uso($callback){
        $this->setCallbackStack($callback);
        return $this;
    }
}
