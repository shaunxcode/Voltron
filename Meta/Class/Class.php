<?php

namespace Voltron\Meta;

class Class 
{
	private $className;
	private $properties;
	private $functions;

	public function __construct($className = false)
	{
		$this->className = $className;
		$this->properties = new \Voltron\Model\Type\Dict(array(), '\Voltron\Meta\Class\Property');
		$this->functions = new \Voltron\Model\Type\Dict(array(), '\Voltron\Meta\Class\Function');
	}

	public static function create($className)
	{
		$obj = new Voltron_Meta_Class($className);
		return $obj;
	}

	public function publicStaticProperty($name, $value)
	{
		$this->properties[] = Class\Property::create($name, $value)->public->static; 
		return $this;
	}

	public function publicStaticFunction($name, $args, $body)
	{
		$this->functions[] = Class\Method::create($name, $args, $body)->public->static;
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
