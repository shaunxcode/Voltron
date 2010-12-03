<?php
	//should implement something like db adapter
namespace Voltron\Db;

class Mysql 
{
		private static $connection = false;
		
		public function __construct($host, $user, $password, $database) 
		{
			if(!self::$connection) {
				self::$connection = new PDO("mysql:host=$host;dbname=$database", $user, $password);
			}
		}

		private function prepareQuery($query, $params = array())
		{
			SqlLog($query);
			SqlLog($params);
			$sth = self::$connection->prepare($query);
			$p = 0;
			foreach($params as $param) {
				$sth->bindValue(++$p, $param);
			}
			return $sth;
		}
		
		public function execute($query, $params = array())
		{
			$sth = $this->prepareQuery($query, $params);
			if(!$sth->execute()) {
				$error = array_combine(
					array('SQLStateCode', 'DriverCode', 'DriverMessage'), 
					$sth->errorInfo());
					
				ErrorLog("DATABASE ERROR:", "Query:" . $query, "Tokens: " . substr_count($query, '?'), "Params (" . count($params) . '): ', $params, "Info:", $error);
				
				throw new Exception("Insert error: [SQLStateCode: {$error['SQLStateCode']} | DriverCode: {$error['DriverCode']} | Message: {$error['DriverMessage']}");
			}
			return true;
		}
		
		public function update($query, $params = array())
		{
			return $this->execute($query, $params);
		}
		
		public function insert($query, $params = array())
		{
	    	$this->execute($query, $params);
			return self::$connection->lastInsertId();
		}
		
		public function query(&$model, $query, $params = array()) 
		{
			return new RecordSet($model, $this->prepareQuery($query, $params));
		}
		
		public function totalRows()
		{
			$sth = $this->prepareQuery("select FOUND_ROWS()");
			$sth->execute();
            $record = $sth->fetch();
            return array_shift($record);
		}
	}
?>
