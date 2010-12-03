<?php

class Voltron_DB_PaginatedResult 
{
	protected $total;
	protected $results;
	protected $page;
	
	public function setTotal($total) 
	{
		$this->total = $total;
		return $this;
	}
	
	public function setPage($page) 
	{
		$this->page = $page;
		return $this;
	}
	
	public function count()
	{
		return $this->total;
	}
	
	public function setResults($results)
	{
		$this->results = $results;
		return $this;
	}
	
	public function asJson()
	{
		return array(
			'__class' => 'PaginatedResult',
			'page' => $this->page,
			'total' => $this->total, 
			'results' => $this->results->asJson());
	}
}
?>