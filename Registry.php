<?php

class Voltron_Registry
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
}