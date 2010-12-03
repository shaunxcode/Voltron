<?php

class Voltron_Meta_Class 
{
	private $className;
	private $properties;
	private $functions;

	public function __construct($className = false)
	{
		$this->className = $className;
		$this->properties = new Voltron_Model_Type_Array(array(), 'Voltron_Meta_Class_Property');
		$this->functions = new Voltron_Model_Type_Array(array(), 'Voltron_Meta_Class_Function');
	}

	public static function create($className)
	{
		$obj = new Voltron_Meta_Class($className);
		return $obj;
	}

	public function publicStaticProperty($name, $value)
	{
		$this->properties[] = Voltron_Meta_Class_Property::create($name, $value)->public->static; 
		return $this;
	}

	public function publicStaticFunction($name, $args, $body)
	{
		$this->functions[] = Voltron_Meta_Class_Function::create($name, $args, $body)->public->static;
		return $this;
	}

	public function write($file = false)
	{
		if(!$file) {
			$parts = explode('_', $this->className);
			if(current($parts) == APPNAME) {
				array_shift($parts);
			}
			$file = fileName(APPROOT, implode('/', $parts) . '.php');
		}

		file_put_contents(
			$file,
			"<?php\n\nclass {$this->className}\n{\n" .  
			$this->properties->map(function($i, $property) { 
				return "\t" . $property->render();
			})->join("\n") . "\n" . 
			$this->functions->map(function($i, $function) {
				return "\t" . $function->render();
			})->join("\n") . "\n}");
	}
}
