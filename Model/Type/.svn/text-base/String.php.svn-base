<?php

class Voltron_Model_Type_String extends Voltron_Model_Type_Abstract
{
	public static function create($value) 
	{
		return new Voltron_Model_Type_String($value);
	}
	
	public function upper()
	{
		return self::create(strtoupper($this->value));
	}
	
	public function lower()
	{
		return self::create(strtolower($this->value));
	}
	
	public function split($by = ' ')
	{
		return new Voltron_Model_Type_Array(explode($by, $this->value), 'Voltron_Model_Type_String');
	}
	
	public function length()
	{
		return strlen($this->value);
	}

	public function asArray()
	{
		return new Voltron_Model_Type_Array(str_split($this->value), 'Voltron_Model_Type_String');
	}
		
	public function replace($x, $y = '')
	{
		return self::create(str_replace($x, $y, $this->value));
	}

	public function ireplace($x, $y)
	{
		return self::create(str_ireplace($x, $y, $this->value));
	}

	public function wrap($left= '"', $right = false) 
	{ 
		return self::create($left . $this->value . ($right ? $right : $left));
	}
	
	public function tag($name)
	{
		return $this->wrap("<$name>", "</$name>");
	}
	
	public function reverse()
	{
		return self::create(strrev($this->value));
	}
	
	public function addSlashes()
	{
		return self::create(addslashes($this->value));
	}
	
	public function isEmpty()
	{
		return empty($this->value);
	}
	
	public function isNotEmpty()
	{
		return !$this->isEmpty();
	}
	
	public function sub($start, $length = false)
	{
		return self::create($length ? substr($this->value, $start, $length) : substr($this->value, $start));
	}
	
	public function subRightOfChar($char)
	{
		return self::create(strrchr($this->value, $char));
	}
	
	public function in()
	{
		$args = func_get_args();
		return newArray(VString, is_array($args[0]) ? $args[0] : $args)->contains($this->value);
	}
	
	public function notIn()
	{
		$args = func_get_args();
		return !$this->in(is_array($args[0]) ? $args[0] : $args);
	}
	
	public function append()
	{
		$args = func_get_args();
		return self::create($this->value . join('', $args));
	}
	
	public function prepend()
	{
		$args = func_get_args();
		return self::create(join('', $args) . $this->value);
	}
}