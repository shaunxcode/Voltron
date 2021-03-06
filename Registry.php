<?php

namespace Voltron;

class Registry
{
	private static $objects = array();
	private static $setters = array();
	
	public static function &set($key, $object)
	{
		self::$objects[$key] = &$object;
		return self::get($key);
	}
	
	public static function &get($key)
	{
		$var = isset(self::$objects[$key]) ? self::$objects[$key] : false;
		if(!$var) {
			if(isset(self::$setters[$key])) {
				$func = self::$setters[$key];
				$var = $func();
			}
		}
		return $var;
 	}

	public static function addSetter($key, $func)
	{
		self::$setters[$key] = $func;
	}

	public static function __callStatic($what, $with) 
	{
		return empty($with) ? self::get($what) : self::set($what, current($with));
	} 
}
