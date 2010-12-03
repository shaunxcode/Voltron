<?php

namespace Voltron; 
	
class Test {
		
	public static function assert($description, $test)
	{
		echo "\033[37m" . $description . "\033[37m";
		if(!$test) {
			echo " [\033[31mFAIL\033[37m]\n";
			return false;
		}
		echo " [\033[32mPASS\033[37m]\n";
		return true;
	}

	public static function CreateTables()
	{
		$tables = func_get_args();
		foreach($tables as $modelName) {
			Registry::get('dbh')->execute(newObject(className(APPNAME, MODEL, $modelName))->getSchema());
		}
	}
	
	public static function MockTable($modelName, $data) 
	{
		$model = newObject(className(APPNAME, MODEL, $modelName));
		$rows = array();
		foreach(explode("\n", $data) as $row) {
			$check = trim($row);
			if(empty($check)) {
				continue;
			}
			
			$row = array_map('trim', explode('|', $row));
			if(!isset($cols)) {
				$cols = $row;	
				continue;
			}

			$rows[] = $model->dispatchRecord(array_combine($cols, $row));
		}

		return $rows;
	}
	
	public static function PopulateTable($modelName, $data)
	{
		$model = newObject(className(APPNAME, MODEL, $modelName));
		foreach(self::MockTable($modelName, $data) as $record) {
			$model->create($record);
		}
		return $model->getAll();
	}
}
