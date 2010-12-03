<?php

class Voltron_Meta_Class_Property extends Voltron_Meta_Class_Node 
{
	private $value;

	public function __construct($name, $value)
	{
		$this->name = $name;
		$this->value = $value;
	}

	public static function create($name, $value)
	{
		$obj = new Voltron_Meta_Class_Property($name, $value);
		return $obj;
	}

	public function render()
	{
		return parent::render() . '$' . $this->name . ' = ' . var_export($this->value, 1) . ';';   
	}
}
