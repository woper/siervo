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

	private $getRoutes;
	private $postRoutes;
	private	$putRoutes;
	private	$deleteRoutes;
	private $auxRoute;

	public function route($route){		
		$this->auxRoute = $route;
		return $this;
	}

	public function get(){
		$this->addRoute(func_get_args(), 'GET');
	}

	public function post(){
		$this->addRoute(func_get_args(), 'POST');
	}

	public function put(){
		$this->addRoute(func_get_args(), 'PUT');	
	}

	public function delete(){
		$this->addRoute(func_get_args(), 'DELETE');
	}

	private function addRoute($args, $requestType){
		switch(count($args)):
			case 1:
				$this->setArrayRoute($this->auxRoute, $args[0], $requestType);
				break;
			case 2:
				$this->setArrayRoute($args[0], $args[1], $requestType);
				break;
		endswitch;
	}

	private function setArrayRoute($key, $callback, $requestType){
		switch($requestType):
			case 'GET':
				$this->getRoutes[$key] = $callback;
				break;
			case 'POST':
				$this->postRoutes[$key] = $callback;
				break;
			case 'PUT':
				$this->putRoutes[$key] = $callback;
				break;
			case 'DELETE':
				$this->deleteRoutes[$key] = $callback;
				break;	
		endswitch;
	}

	private function getRequestMethod(){
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		if(($requestMethod == 'POST') && (array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER))):
			if($_SERVER == 'DELETE'):
				$requestMethod = 'DELETE';
			elseif($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT'):
				$requestMethod = 'PUT';
			else:
				throw new Exception('Unexpected Header');
			endif;
		endif;
		return $requestMethod;
	}	
}
