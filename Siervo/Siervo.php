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
     * @var callback|boolean
     */
    public $notFoundCallback;

    /**
     * @var array() Contiene los distintos
     * entornos registrados (key) y su
     * comportamiento asociado mediante callback (value).
     */
    private $environments;

    public function __construct(){
        $this->setDefaultEnv();
        $this->setEnv();
        $this->setPath();
        $this->setRPath();
        $this->router = new Router($this);
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
     * @return $this|null|Siervo
     */
	public function get(){
		return call_user_func_array(array($this->router, 'get'), func_get_args());
	}

    /**
     * Post
     *
     * Le comunica al objeto Router que registre
     * una ruta y un comportamiento para esa ruta
     * cuando el request method es POST.
     *
     * @return $this|null|Siervo
     */
	public function post(){
		return call_user_func_array(array($this->router, 'post'), func_get_args());
	}

    /**
     * Put
     *
     * Le comunica al objeto Router que registre
     * una ruta y un comportamiento para esa ruta
     * cuando el request method es PUT.
     *
     * @return $this|null|Siervo
     */
	public function put(){
		return call_user_func_array(array($this->router, 'put'), func_get_args());
	}

    /**
     * Delete
     *
     * Le comunica al objeto Router que registre
     * una ruta y un comportamiento para esa ruta
     * cuando el request method es DELETE.
     *
     * @return $this|null|Siervo
     */
	public function delete(){
		return call_user_func_array(array($this->router, 'delete'), func_get_args());
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
     * Asocia la funcion anonima y la ejecuta
     * pasandole los parámetros, en caso de
     * tenerlos.
     *
     * @param $callback
     * @param array $args
     * @return mixed
     */
    public function dispatch($callback, $args = array()){
        if(is_callable($callback)):
            if($callback === $this->notFoundCallback):
                # ver si el hay creado un objeto response, si no setearlo a 404.
            endif;
            $callback->bindTo($this, __CLASS__);
            return call_user_func_array($callback, $args);
        else:
            # Proximamente un response 404, usando el Objeto Response.
            //header("HTTP/1.0 404 Not Found");
            //http_response_code(404);
            throw new \RuntimeException();
        endif;
    }

    /**
     * Get Request
     *
     * Retorna la request contenida por siervo.
     *
     * @return Request
     */
    public function getRequest(){
        return $this->request;
    }
}
