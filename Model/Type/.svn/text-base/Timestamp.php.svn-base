<?php

class Voltron_Model_Type_Timestamp extends Voltron_Model_Type_Abstract
{
	public function asDateTime()
	{
		return newType('DateTime')->setValue(newObject('DateTime')->setTimestamp($this->value));	
	}
}