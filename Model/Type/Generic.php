<?php

namespace Voltron\Model\Type;

class Generic 
{
	public function __construct($val)
	{
		foreach($val as $k => $v) {
			$this->$k = $v;
		}
	}
}
