<?php

namespace Voltron\Model\Type;

class Integer extends Base
{
	public function __construct($value = 0)
	{
		$this->value = (int)$value;
	}
	
	public function create($value) 
	{
		return new Integer($value);
	}
	
	public function parseValue($value)
	{
		return $value instanceof Base ? $value->value() : (int)$value;
	}

	public function times($value)
	{
		return $this->create($this->value * $this->parseValue($value));
	}
	
	public function greater($than)
	{
		return $this->value > $this->parseValue($than);
	}
	
	public function add($value)
	{
		return $this->create($this->value + $this->parseValue($value));
	}
	
	public function sub($value) 
	{
		return $this->create($this->value - $this->parseValue($value));
	}

	//** SHould return a float?
	public function div($value)
	{
		return $this->create($this->value / $this->parseValue($value));
	}

	public function asString()
	{
		return newType(VString, $this->value);
	}
}
