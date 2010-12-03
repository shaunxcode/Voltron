<?php

class Voltron_Session
{
	private $session;
	
	public function __construct($base = false)
	{
		if($base) {
			$this->session =& $_SESSION[$base];
		} else {
			$this->session =& $_SESSION;
		}
	}
	
	public function &get($key, $default = false)
	{
		$var = isset($this->session[$key]) ? $this->session[$key] : $default;
		return $var;
	}
	
	public function set($key, $value)
	{
		ErrorLog("SET $key TO", $value);
		$this->session[$key] = $value;
	}
	
	public function __set($key, $value)
	{
		$this->set($key, $value);
	}
	
	public function &__get($key)
	{
		return $this->get($key);
	}
	
	public function delete($key)
	{
		unset($this->session[$key]);
	}
}
