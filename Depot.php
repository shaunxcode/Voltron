<?php

namepsace Voltron;

class Depot
{
	public static function dispatch()
	{
		$routes = Registry::get('Routes');
		
		$parts = explode('/', $_SERVER['REQUEST_URI']);
		array_shift($parts);

		if($parts[0] == URLBASE) {  
			array_shift($parts);
		}

		if(count($parts) == 1) {
			$parts[] = false;
		}

		list($routeName, $methodName) = $parts;

		if(!$route = $routes->getRoute($routeName, $parts)) {
			$route = $routes->getRoute('_Default', $parts);
		}

		if($routeName == $parts[0]) {
			array_shift($parts);
		}

		if($route->type == REDIRECT) {
			die(header("location:{$route->url}"));
		}
		
		if($route->type == MODEL) {
			die(json_encode(call_user_func_array(array(newObject(APPNAME . '_Model_' . $route->model), $route->method), $route->args)));
		}

		$controllerClass = APPNAME . '_Controller_' . $route->controller;
		$controller = new $controllerClass($route->controller, $routeName);
		$appControllerName = $controller->layout ? $controller->layout : APPNAME;
		$appControllerClass = APPNAME . '_Controller_' . $appControllerName;
		$appController = new $appControllerClass($appControllerName, UI::draw($controller->__dispatch($route->method, $parts)), $routeName);
		
		echo $appController->__dispatch('index');
	}
}
