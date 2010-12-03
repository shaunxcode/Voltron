<?php

class Voltron_View
{
	protected $env;
	protected $Session;
	
	public function __construct($env)
	{
		$this->env = $env;	
		$this->Session = Voltron_Registry::get('Session');
	}

	public function route($key) 
	{
		$key = str_replace(array(' ', '/'), array('', 'And'), $key);
		if($route = Voltron_Registry::get('Routes')->getRoute($key)) {
			return '/' . URLBASE . '/' . $key;
		}
		
		throw new Exception("Route: '$key' does not exist");	
	}
	
	public function partial($file, $extraEnv = array())
	{
		$parts = explode('_', get_class($this));
		array_pop($parts);
		$parts[] = $file;
		foreach($extraEnv as $key => $val) $this->env->$key = $val;
		return newObject(implode('_', $parts), $this->env)->render();
	}
	
	public function widget($controller, $method, $args = array())
	{
		return newObject(className(APPNAME, 'Controller', $controller), $controller)->__dispatch($method, $args);
	}
		
	public function render()
	{

	}
	
	public function __get($what)
	{		
		return isset($this->env->$what) ? $this->env->$what : false;
	}
}
