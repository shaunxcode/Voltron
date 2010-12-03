<?php

namespace Voltron;

class Util
{
	public static function getExtendedStaticArray($className, $arrayName)
	{
		$vars = get_class_vars($className);
		return ($parent = get_parent_class($className)) ?
			array_merge(self::getExtendedStaticArray($parent, $arrayName), $vars[$arrayName])
			:
			$vars[$arrayName];
	}
	
	public static function methodExists($object, $method)
	{
		return is_object($object) && is_callable(array($object, $method));
	}

	public static function arrayToHash($array)
	{
		if(count($array) % 2) {
			throw new Exception("Array must be of equal length to create hash. Got: " . json_encode($array));
		}
		
		$hash = array();
		while(!empty($array)) {
			$hash[array_shift($array)] = array_shift($array); 
		}
		
		return $hash; 
	}

	public static function generateCode($length = 8, $charSet = 'ACDEFGHJKLMNPQRTUVWXY34679')
	{
        $chars = str_split($charSet);
        $code = array_rand($chars, $length);
        foreach($code as $i => $char) {
			$code[$i] = $chars[$char];	
		}
        
		return implode('', $code);
	}
}
