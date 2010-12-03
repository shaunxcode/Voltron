<?php

class Voltron_Directory 
{
	private $handle;
	private $dirName;
	
	public function __construct($dirName)
	{
		$this->dirName = $dirName;
		$this->handle = opendir($dirName);
	}
	
	public function tree()
	{	
		$files = newArray('String');
		while(($file = readdir($this->handle)) !== false) if($file !== '.' && $file !== '..' && $file[0] !== '.') {			
			$dfname = fileName($this->dirName, $file);
			$files[$file] = filetype($dfname) == 'dir' ? newObject('Voltron_Directory', $dfname)->tree() : $file;
		}

		return $files;		
	}

	public static function getTree()
	{
		$args = func_get_args();
		return newObject('Voltron_Directory', implode('/', $args))->tree();
	}

	public static function make($dirName)
	{
		if(!file_exists($dirName)) {
			if(!mkdir($dirName)) {
				throw new Exception("Can not create $dirName");
			}
		}
		return true;
	}
}
