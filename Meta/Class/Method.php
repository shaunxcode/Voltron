<?php

namespace Voltron\Meta\Class;

class Method extends Node 
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
		$obj = new Method($name, $args, $body);
		return $obj;
	}

	public function render()
	{
		return parent::render() . "function {$this->name}({$this->args}){\n\t\t{$this->body};\n\t}\n";
	}
}
