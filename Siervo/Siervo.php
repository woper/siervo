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

	public function route($route){		
		return $this;
	}

	public function get(){
		$this->addRoute(func_get_args, 'GET');
	}
	public function post(){}
	public function put(){}
	public function delete(){}

	private function addRoute($args, $requestType){
		switch(count($args)){
			case 1:
				break;
			case 2:
				break;
		}
	}
}
