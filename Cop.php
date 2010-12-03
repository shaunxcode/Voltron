<?php

class Voltron_Cop
{
	protected $checkField = 'authenticated';
	
	/*
		obviously one could over-ride this method and have it do some thing far
		more meaningful i.e. check a database etc. 
	*/
	public function checkUserClearance($controller = false, $method = false, $level = false)
	{
		//by default we do not do anything w/ the level - but you definitely might want to
		if($level == OPEN) {
			return true;
		}
		
		return Voltron_Registry::get('Session')->get($this->checkField, false);
	}
}