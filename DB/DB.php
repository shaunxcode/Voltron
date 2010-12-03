<?php

namespace Voltron;

class DB 
{
	public static function createHandle()
	{
		//load the app db config
		$className = APPNAME . '\Config\Database';
		$config = new $className;
		$dbhClassName = '\Voltron\DB\\' . $config->type;
		return Registry::dbh(new $dbhClassName($config->host, $config->user, $config->pass, $config->database));
	}
}
