<?php

namespace Voltron\Model\Type;

class Timestamp extends Base
{
	public function asDateTime()
	{
		return newType('DateTime')->setValue(newObject('DateTime')->setTimestamp($this->value));	
	}
}
