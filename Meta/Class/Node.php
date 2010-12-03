<?php

class Voltron_Meta_Class_Node
{
	protected $name;
	protected $private;
	protected $public;
	protected $static;

	public function __get($what)
	{
		if(property_exists($this, $what)) {
			$this->$what = true;
		}
		return $this;
	}

	public function render() 
	{
		return ($this->private ? 'private' : 'public') . ' ' . ($this->static ? 'static' : '') . ' ';
	}
}
