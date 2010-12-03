<?php
require_once APPROOT . '/library/Voltron/Model/Type.php';
session_start();
define('VOLTRON', 'Voltron');

if(!defined('Voltron_DefaultCop')) {
	define('Voltron_DefaultCop', 'Voltron_Cop');
}

if(!defined('SESSION_BASE')) {
	define('SESSION_BASE', false);
}
define('PEARPATH', '/usr/share/pear');
define('RESTRICTED', 'RESTRICTED');
define('POST', 'POST');
define('GET', 'GET');
define('OPEN', 'OPEN');
define('JSON', 'JSON');
define('HTML', 'HTML');
define('VIEW', 'VIEW');
define('FILE', 'FILE');
define('IMAGE', 'IMAGE');
define('MODEL', 'MODEL');
define('CONTROLLER', 'Controller');
define('REDIRECT', 'REDIRECT');
define('VAL', 'val');
define('KEY', 'key');
define('val', 'val');
define('key', 'key');
define('VString', 'String'); 
define('VArray', 'Array'); 
define('VRange', 'Range');
define('VInteger', 'Integer');
define('VGeneric', 'Generic');
define('VPrimitive', 'Primitive');
define('VDate', 'DateTime');
define('VDateTime', 'DateTime');
define('x', 'key'); 
define('y', 'val');
define('k', 'key');
define('v', 'val');

/* this replaces the need to use newObject($string, $costructArg) as you can now do
   N::ClassName($a, $b)->method() etc.
*/
class N {	
	public static function __callStatic($className, $args)
	{
	    $class = new ReflectionClass($className);
	    return $class->newInstanceArgs($args);
	}
}

class Model {
	public static function __callStatic($modelName, $args)
	{
		return newObject(className(APPNAME, MODEL, $modelName));
	}
}

function puts($what)
{
	echo $what . "\n";
}

function fileName() 
{
	$args = func_get_args();
	return implode('/', $args);
}

function className()
{
	$args = func_get_args();
	return implode('_', $args);	
}

function newObject($class, $constructArg = false)
{
	return $constructArg ? new $class($constructArg) : new $class;
}

function apply()
{
	$args = func_get_args();
	return call_user_func_array(array_shift($args), $args);
}

function newType($type, $constructArg = false)
{
	return newObject('Voltron_Model_Type_' . $type, $constructArg);
}

function primToType($value)
{
	return newType(is_numeric($value) ? VInteger : VString, $value);
}

function typeToPrim($value)
{
	if(is_array($value)) {
		$array = array();
		foreach($value as $k => $v) {
			$array[$k] = typeToPrim($v);
		}
		return $array; 
	}

	return is_null($value) || is_scalar($value) ? $value : (is_object($value) && is_callable(array($value, 'value')) ? $value->value() : false);
}

function newArray() 
{
	$args = func_get_args();
	$type = array_shift($args);
	
	//this is so you can call newArray as newArray('String', array('a','b','c')) and newArray('String', 'a', 'b', 'c'); 
	if(count($args) == 1 && is_array($args[0])) {
		$args = array_shift($args);
	}
	
	return newObject('Voltron_Model_Type_Array', $args)->setClass('Voltron_Model_Type_' . $type);
}

function newRange($min, $max, $step = 1) {
	return newArray(VInteger, range(typeToPrim($min), typeToPrim($max), typeToPrim($step)));
}

function A()
{
	//determine type of array etc. 
}

function L($var) 
{
	return newObject('Voltron_FluentLambda', $var);
}

function I($field)
{
	return L(key)->$field;
}

function M($field) 
{
	return L(val)->$field;
}

function V($var)
{
	
}

function K($var)
{
	
}

function N()
{
	$args = func_get_args();
	$type = array_shift($args);
	
	$list = newArray(VString); 
	foreach($args as $arg) {
		$list->push($arg instanceof Voltron_FluentLambda ? ('call_user_func_array(create_function(\'' . $arg->getOperatingOn() . '\',\'' . $arg->getLambdaString() .'\'), array(' . $arg->getOperatingOn() . '))') : ($arg == key || $arg == val ? '$' . $arg : $arg));
	}
	
	return create_function('$key, $val', 'return new' . $type . '(' . $list->join(',') . ');');
}

function O(&$obj)
{
	//build a closure over obj
}

function S($val) {
	return newType(VString, $val);
}

function W() {
	$args = func_get_args();
	$operator = strtolower(array_shift($args));
	
	return in_array($operator, array('and', 'or')) ? 
		array('type' => $operator, 'value' => $args) 
		:
		array('type' => $operator, 'field' => array_shift($args), 'value' => in_array($operator, array('in', 'between')) ? $args : array_shift($args));
}

function isArray($obj)
{
	return is_array($obj) || $obj instanceof ArrayAccess; 
}

function VoltronLint($file)
{
	if(!defined('LINTFILES')) return true;
	
    exec("php -l $file", $output);

    if(current($output) != "No syntax errors detected in $file") {
        if(function_exists('ErrorLog')) {
            ErrorLog("php -l $file", "TRYING TO LOAD $file and encounted syntax errors:", $output, "No syntax errors detected in $file", current($output));
        }
    }
    return true;
}

function __autoload($class_name) 
{
	$parts = explode('_', $class_name);
	$first = array_shift($parts);

	//if nothing after prefix assume same name as prefix
	if(empty($parts)) {
		$parts[] = $first;
	}
	
	$location = APPROOT . ($first == APPNAME ? '' : '/library/' . $first) . '/' . implode('/', $parts);
	$file = $location . (file_exists($location) ? '/' . end($parts) : '') . '.php';
	if(file_exists($file) && VoltronLint($file)) {
		require_once $file;
	} else {
		if(defined('PEARPATH')) {
			//perhaps it is a PEAR lib
			$file = PEARPATH . '/' . $first . '/' . $parts[0] . '.php';
			if(file_exists($file)) {
				require_once $file;
			}
		
			$file = PEARPATH . '/' . $first . '.php';
			if(file_exists($file)) {
				require_once $file;
			}
		}
	}
}

$loggerClassName = APPNAME . '_Config_Logger';
if(class_exists($loggerClassName)) {
	Voltron_Registry::set('Logger', new $loggerClassName);
	function InfoLog()
	{	
		$args = func_get_args();
		return Voltron_Registry::get('Logger')->writeToLog('info', $args); 
	}
	
	function SqlLog()
	{	
		$args = func_get_args();
		return Voltron_Registry::get('Logger')->writeToLog('sql', $args); 
	}
	
	function ErrorLog()
	{
		$args = func_get_args();
		return Voltron_Registry::get('Logger')->writeToLog('error', $args);
	}
	
	function EmailLog()
	{
		$args = func_get_args();
		return Voltron_Registry::get('Logger')->writeToLog('email', $args);
	}
	
}

function VoltronErrorHandler($no, $str, $file, $line)
{
    ErrorLog(array('date' => time(), 'num' => $no, 'msg' => $str, 'file' => $file, 'line' => $line));
}

set_error_handler('VoltronErrorHandler');

function VoltronExceptionHandler($exception)
{
    ErrorLog($exception);
}

set_exception_handler('VoltronExceptionHandler');

unset($loggerClassName);
Voltron_Registry::set('Routes', newObject(APPNAME . '_Config_Routes'));
Voltron_Registry::set('Request', new Voltron_Request);
Voltron_Registry::set('Session', new Voltron_Session(SESSION_BASE));
/* 
	This faciliates the ability for requests which do not use the DB to NOT instantiate db handle,
  	thus only created first time dbh is accessed in registry 
*/
Voltron_Registry::addSetter('dbh', function() { 
	return Voltron_DB::createHandle(); 
});
