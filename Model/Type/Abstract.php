<?php

namespace Voltron\Model\Type;

abstract class Base
{
	protected $value;

	public function __construct($value = '')
	{
		$this->value = $value;
	}

	public function value()
	{
		return $this->value;
	}
	
	public function setValue($value)
	{
		$this->value = $value;
		return $this;
	}
	
	public function __toString()
	{
		return (string)$this->value;
	}

	public function asJson()
	{
		return $this->value;
	}
	
	public function is($x) 
	{
		return $this->value == typeToPrim($x);
	}

	public function isIn()
	{
		$args = func_get_args();
		foreach($args as $arg) {
			if($this->is($arg)) {
				return true;
			}
		}
		return false;
	}
		
	public function isNot($x)
	{
		return !$this->is($x);
	}
	
	public function asString()
	{
		return $this->__toString();
	}
	
	public function __get($val)
	{
		if(is_callable(array($this, $val))) {
			return $this->$val();
		}
		throw new Exception('Trying to call ' . $val . ' and does not exist for ' . get_class($this));
	}
}
