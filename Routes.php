<?php

namespace Voltron;

class Routes
{
	public function getRoute($key, $uri = array())
	{
		if(property_exists($this, $key)) {
			$route = $this->$key;
			if(is_string($route)) {
				$route = array('type' => 'REDIRECT', 'url' => $route);
			} else if(pos($route) == MODEL) {
				$route['type'] = array_shift($route);
				$route['model'] = array_shift($route);
				$route['method'] = array_shift($route);
				array_shift($uri);
				$route['args'] = $uri;
			} else {
				$route['type'] = CONTROLLER;
			
				if(!isset($route['controller'])) {
					$route['controller'] = array_shift($route);
				}
			
				if(!isset($route['method'])) {
					$route['method'] = array_shift($route);
				}
			}
			
			return (object)$route;
		}
	}
}
