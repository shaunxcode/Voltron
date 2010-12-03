<?php

namespace Voltron\Model;

class GenericRecord
{
	public function __construct($data)
	{
		$this->data = $data;
	}
	
	public function asJson()
	{
		$result = array();
		
		foreach($this->data as $field => $value) {
			$result[$field] = is_callable(array($value, 'asJson')) ? $value->asJson() : $value;
		}
		
		return $result;
	}
}
