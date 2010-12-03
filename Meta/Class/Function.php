<?php

class Voltron_Meta_Class_Function extends Voltron_Meta_Class_Node 
{
	protected $args;
	protected $body; 

	public function __construct($name, $args, $body) 
	{
		$this->name = $name;
		$this->args = $args; 
		$this->body = $body;
	}

	public static function create($name, $args, $body)
	{
		$obj = new Voltron_Meta_Class_Function($name, $args, $body);
		return $obj;
	}

	public function render()
	{
		return parent::render() . "function {$this->name}({$this->args}){\n\t\t{$this->body};\n\t}\n";
	}
}
