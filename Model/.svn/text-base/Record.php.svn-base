<?php

class Voltron_Model_Record
{
	public $data;
	private $_modelName;
	private $_model;
	
	public function __construct($data = array())
	{
		$this->data = (object)$data;
	}
	
	public function setAndIs($key, $val) 
	{
		return $this->$key ? typeToPrim($this->$key) == typeToPrim($val) : false;
	}
	
	public function setAndNotEmpty($key)
	{
		return $this->$key && $this->$key->isNotEmpty();
	}
	
	public function setModelName($name)
	{
		$this->_modelName = $name;
		return $this;
	}
	
	public function getModel()
	{
		if(!$this->_model) {
			$this->_model = newObject($this->_modelName);
		}

		return $this->_model;
	}
	
	public function getFields()
	{
		return Voltron_Model::getFieldList($this->_modelName);
	}
	
	public function getField($fieldName)
	{
		return Voltron_Model::getFieldList($this->_modelName, $fieldName);
	}
	
	public function get($field)
	{
		if($fieldType = $this->getField($field)) {
			if($fieldType->class == Type::JoinMany) {
				return newObject(APPNAME . '_Model_' . $fieldType->spec->toModel)->getWhereFields($fieldType->spec->toField, $this->{$fieldType->spec->fromField});
			}
			else if($fieldType->class == Type::Join) {
				return newObject(APPNAME . '_Model_' . $fieldType->spec->toModel)->get($this->{$fieldType->spec->fromField});
			} else if($fieldType->class == Type::Calculated) {
				$method = reset($fieldType->spec);
				return $this->$method();
			} else {
				if(isset($this->data->$field)) {
					return newObject('Voltron_Model_Type_' . $fieldType->class, $this->data->$field);
				}
			}
		} else {
			/* this is a hack to allow legacy $this->Model access where it expects model to be uppercased and field $this->model to not be... */
			if(strtoupper($field[0]) == $field[0]) {
				$className = APPNAME . '_Model_' . $field;
				if(class_exists($className)) {
					$this->$className = new $className;
					return $this->$className;
				}
			}
			
			//allow generic voltron model record 
			if(isset($this->data->$field)) {
				return !is_scalar($this->data->$field) ? $this->data->$field : newObject('Voltron_Model_Type_String', $this->data->$field);
			}
			
			if(is_callable(array($this, $field))) {
				$this->$field = $this->$field();
				return $this->$field;
			}
				
			//allow get methods which are not fields, set so subsequent calls are cached
			if(is_callable(array($this, 'get' . ucfirst($field)))) {
				return $this->{'get' . ucfirst($field)}();
			}
		}
		return false;
	}
		
	public function __get($field)
    {
		return $this->get($field);
	}

	public function set($field, $value)
	{
		if($this->getField($field)) {
			$this->data->$field = $value;
		}
		return $this;
	}
	
	public function asJson()
	{
		$record = array();
		foreach($this->getFields() as $field => $type) {
			if($type->class == Type::Calculated) {
				$method = reset($type->spec);
				$this->data->$field = $this->$method();
				if(Voltron_Util::methodExists($this->data->$field, 'asJson')) {
					$this->data->$field = $this->data->$field->asJson();
				}
			}

			$record[$field] = isset($this->data->$field) && $type->class != Type::Password ? $this->data->$field : null;
		}
		return $record;
	}
	
	public function save()
	{
		$this->getModel()->update($this);
		return $this->getModel()->get($this->id);
	}
}
