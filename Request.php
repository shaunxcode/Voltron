<?php

namespace Voltron;

class Request
{
	protected $vars;

	public function __construct()
	{
		$this->vars = (object)$_REQUEST;
	}

	public function get($what, $default = false)
	{
		return isset($this->vars->$what) ? (is_array($this->vars->$what) ? (object)$this->vars->$what : $this->vars->$what) : $default;
	}
	
	public function __get($what)
	{
		return $this->get($what);
	}
	
	public function getArgs()
	{
		return $this->vars;
	}
}
