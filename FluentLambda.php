<?php

class Voltron_FluentLambda
{
	private $stack = array(); 
	private $operatingOn; 
	
	public function __construct($var)
	{
		$this->operatingOn = '$' . $var;
		$this->stack = newArray(VString);
		$this->stack->push($this->operatingOn);
	}
	
	public function getOperatingOn()
	{
		return $this->operatingOn;
	}
	
	public function __call($method, $args)
	{
		$list = newArray(VString);
		foreach($args as $arg) {
			if($arg instanceof Voltron_FluentLambda) {
				$arg = $arg->getLambda();	
			}
			
			$list->push($arg == key || $arg == val ? '$' . $arg : '"' . addslashes($arg) . '"'); 
		}

		$this->stack->push($method . '(' . $list->join(',') . ')');

	
		return $this;
	}

	public function __get($method)
	{
		$this->stack->push($method);
		return $this;
	}
	
	public function getLambdaString()
	{
		return $this->stack->join('->') . ';';
	}
	
	public function getLambda()
	{
		return create_function('$key, $val', 'return ' . $this->getLambdaString());
	}
}