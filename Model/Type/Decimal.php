<?php

class Voltron_Model_Type_Decimal extends Voltron_Model_Type_Integer
{
	public function __construct($value)
	{
		$this->value = $this->parseValue($value);
	}
	
	public function create($value) 
	{
		return new Voltron_Model_Type_Decimal($value);
	}
	
	public function parseValue($value)
	{
		return $value instanceof Voltron_Model_Type_Abstract ? $value->value() : (float)$value;
	}
}