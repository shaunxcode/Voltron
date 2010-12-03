<?php

namespace Voltron;

class File
{
	private $handle;
	
	public function __construct($filename, $mode = 'a+')
	{
		$this->handle = fopen($filename, $mode);
	}
	
	public function putLine($line)
	{
		if(!$this->handle) {
			throw new Exception("Handle is not open");
		}
		
		fwrite($this->handle, $line . "\n");
		return $this;
	}
	
	public function close()
	{
		fclose($this->handle);
		$this->handle = false;
	}
	
	public function __destruct()
	{
		fclose($this->handle);
	}
	
	public static function contents($filename)
	{
		return file_get_contents($filename);
	}
	
	public static function copy($source, $destination)
	{
		return copy($source, $destination) ? $destination : false;
	}
	
	public static function exists($filename)
	{
		return file_exists($filename);
	}
	
	public static function remove($filename)
	{
		return self::exists($filename) ? unlink($filename) : true;
	}
}
?>
