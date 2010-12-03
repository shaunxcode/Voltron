<?php

namespace Voltron;

class Logger
{
	protected $infoFile;
	protected $sqlFile;
	protected $errorFile;
	protected $emailFile;
	
	protected $handles = array();
	
	public function __construct()
	{
		$this->handles['info'] = new File($this->infoFile);
		$this->handles['sql'] = new File($this->sqlFile);
		$this->handles['error'] = new File($this->errorFile);
		$this->handles['email'] = new File($this->emailFile);
	}
	
	public function writeToLog($name, $message) 
	{
		if(is_array($message)) {
			foreach($message as $n => $x) {
				$message[$n] = is_bool($x) ? ($x ? 'TRUE' : 'FALSE') : (is_scalar($x) ? $x : var_export($x, true));
			}
			$message = implode("\n\n", $message);
			$return = $x;
		} else {
			$return = $message;
		}
		
		$this->handles[$name]->putLine($message);
		return $return;
	}
}
