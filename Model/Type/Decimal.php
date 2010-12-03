<?php

namespace Voltron\Model\Type;

class Decimal extends Integer
{
	public function __construct($value)
	{
		$this->value = $this->parseValue($value);
	}
	
	public function create($value) 
	{
		return new Decimal($value);
	}
	
	public function parseValue($value)
	{
		return $value instanceof Abstract ? $value->value() : (float)$value;
	}
}
