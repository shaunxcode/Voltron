<?php

namespace Voltron\DB;

class RecordSet implements Iterator, Countable
{
	private $position = 0;
	private $count = 0;
	private $model = false;
	private $query;
	private $result = false;
	private $current = false;
	
	public function __construct(&$model, $query)
	{
		$this->model = &$model;
		$this->query = $query;
	}

	private function load()
	{
		if(!$this->result) {
			$this->result = $this->query->execute();
		}
	}
	
	public function isEmpty() {
		return $this->count() == 0;
	}
	
	public function notEmpty() {
		return !$this->isEmpty();
	}
	
	public function count()
	{
		if(!$this->count) {
			$this->load();
			$this->count = $this->query->rowCount();
		}
		return $this->count;
	}
	
	public function valid() 
	{
		return $this->count() && $this->position <= $this->count();
	}
	
	function rewind() 
	{
		$this->load();
		$this->position = 0;
	}
	
	function next()
	{
		if(!$this->result) {
			$this->load();
		}
		
		if($this->valid()) {		
			$this->position++;
			$this->current = $this->model->dispatchRecord($this->query->fetchObject());
			return $this->current;
		}
	}
	
	function current() 
	{
		if(!$this->current) {
			$this->next();
		}
		
		return $this->current;
	}
	
	function key()
	{
		return $this->position;
	}

	function asArray()
	{
		$dataSet = newType(VArray)->setClass($this->model->getRecordClassName());
		if($this->count()) {
			do {
				$dataSet->push($this->next());
			} while ($this->position < $this->count());
		}
		return $dataSet;
	}
	
	public function sum($field)
	{	
		$total = primToType(0);
		foreach($this as $row) {
			$total = $row->$field->add($total);
		}
		return $total;
	}
	
	public function asHash($keyField, $valField = false)
	{
		$hash = array();
		foreach($this as $row) {
			$hash[typeToPrim($row->$keyField)] = $valField ? typeToPrim($row->$valField) : $row;
		}
		return $hash;
	}
	
	function asJson()
	{		
		$dataSet = array();
		if($this->count()) {
			do {
				$dataSet[] = $this->next()->asJson();
			} while ($this->position < $this->count());
		}
		return $dataSet;
	}
}
