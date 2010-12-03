<?php

class Voltron_DB 
{
	public static function createHandle()
	{
		//load the app db config
		$className = APPNAME . '_Config_Database';
		$config = new $className;
		$dbhClassName = 'Voltron_DB_' . $config->type;
		return Voltron_Registry::set('dbh', new $dbhClassName($config->host, $config->user, $config->pass, $config->database));
	}
}
