<?php

class Voltron_Model
{
	protected $table;
	protected $primaryKey = 'id';
	protected $recordName = false;
	
	public static $fields = array();
	public static $fieldCache = array();
	public $Session;
		
	public function getTable()
	{
		return $this->table;
	}
	
	public static function getFieldList($modelName = false, $fieldName = false)
	{
		/* allow for generic */
		if(empty($modelName)) {
			return false;
		}
		
		if(!isset(self::$fieldCache[$modelName])) {
			self::$fieldCache[$modelName] = array();
			foreach(Voltron_Util::getExtendedStaticArray($modelName, 'fields') as $fname => $type) {
				if(!is_array($type)) {
					$type = array($type);
				}

				self::$fieldCache[$modelName][$fname] = (object)array(
					'name' => $fname,
					'class' => array_shift($type),
					'spec' => (object)$type);
			}
		}

		return $fieldName ?
			(isset(self::$fieldCache[$modelName][$fieldName]) ? self::$fieldCache[$modelName][$fieldName] : false)
			: 
			self::$fieldCache[$modelName];
	}
	
	public function getFields()
	{
		return Voltron_Model::getFieldList(get_class($this));
	}
	
	public function __construct()
	{
		$this->Session = Voltron_Registry::get('Session');
	}
			
	public function __get($field)
	{
		$className = APPNAME . '_Model_' . $field;
		if(class_exists($className)) {
			$this->$className = new $className;
			return $this->$className;
		} else {
			throw new Exception("Trying to access $field which is not part of model " . get_class($this));
		}
	}
	
	/* Return String */
	public function getRecordClassName()
	{
		static $className = false;

		if(!$className) {
			$thisClass = get_class($this);
			list($prefix, $rest) = explode('_Model_', $thisClass); 
			$pieces = explode('_', $rest);
			$last = array_pop($pieces);

			if(empty($pieces)) {
				if(file_exists(fileName(APPROOT, 'Model', str_replace('_', '/', $rest)))) {
					$pieces[] = $last;
				}
			}
			
			$pieces[] = 'Record';
			$pieces[] = $last;
			
			array_unshift($pieces, 'Model');
			$recordClass = $prefix . '_' . implode('_', $pieces);
			$className = class_exists($recordClass) ? $recordClass : ($this->recordName ? $this->recordName : 'Voltron_Model_Record');
		}
		
		return $className;
	}

	/* Return array */
	private function _buildFields(Voltron_Model_Record $record)
	{
		$fields = array();
		foreach($this->getFields() as $field => $type) {
			//ignore if type is calculated
			if($type->class == Type::Calculated || $type->class == Type::Primary || $type->class == Type::Password) {
				continue;
			}
			
			if(isset($record->data->$field)) {
				$val = $record->data->$field;
				$fields["`$field`"] = is_scalar($val) ? $val : $val->value();
			}
		}
		return $fields;
	}
	
	protected function beforeCreate(Voltron_Model_Record $record)
	{
		return $record;
	}
	
	protected function beforeCreateOrUpdate(Voltron_Model_Record $record)
	{
		return $record;
	}
	
	/* Return Int */
	public function create()
	{
		$args = func_get_args();
		$record = $this->beforeCreateOrUpdate($this->beforeCreate(count($args) == 1 ? current($args) : $this->dispatchRecord(Voltron_Util::arrayToHash($args))));
		
		$fields = $this->_buildFields($record);

		$return = $this->get(Voltron_Registry::get('dbh')->insert(
			"insert 
			   into `{$this->table}`(" . implode(',', array_keys($fields)) . ") 
			 values (" . implode(',', array_fill(0, count($fields), '?')). ")", 
			$fields));
			
		return $this->primaryKey ? $return : true;
	}
	
	/* Return Int */
	public function update(Voltron_Model_Record $record, $clause = false) 
	{
		//TODO implement actually using clause for ocmplex updates
		
		$fields = $this->_buildFields($record);
		$sets = array();
		foreach(array_keys($fields) as $field) {
			$sets[] = "$field = ?";	
		}
		
		$id = $record->id;
		$fields[`id`] = $id;
		
		return $this->get(Voltron_Registry::get('dbh')->update(
			"update `{$this->table}`
			    set " . implode(', ', $sets) . " 
			  where `id` = ?", $fields) ? $id : false);
	}
	
	public function deleteWhere($clause)
	{
		//build the actual sql freund
		$where = '';
		$params = array();
		$custom = '';
		
		if($clause['type'] == 'rawsql') {
			$custom = $clause['field'];
		} else {
			$clause = self::whereBuilder($clause); 
			$where = 'where ' . current($clause->sql);
			$params = $clause->params;
		}
			
		if(empty($where)) {
			throw new Exception("We MUST have a where clause for a delete statement");
		}

		Voltron_Registry::get('dbh')->execute("delete from {$this->table} {$custom} {$where}", $params);
		return true;
	}

	public function deleteWhereFields()
	{
		$args = func_get_args();
		return $this->deleteWhere(self::composeWhere($args));
	}

	/* Return Boolean */
	public function delete($id)
	{
		return $this->deleteWhereFields($this->primaryKey, $id);
	}
			
	/* Return Boolean */
	public function updateAll(Voltron_Model_Type_Array $records)
	{
		foreach($records as $record) {
			$this->update($record);
		}
		
		return true;
	}
	
	/* Return Boolean */
	public function saveAll(Voltron_Model_Type_Array $records)
	{
		foreach($records as $record) {
			$record->setModelName(get_class($this));
			$this->{$record->id->isNot(0) ? 'update' : 'create'}($record);
		}
		
		return true;
	}
	
	public static function whereBuilder($clauses)
	{
		if(!is_numeric(key($clauses))) {
			$clauses = array($clauses);
		}
		
		if(is_array(current($clauses)) && is_numeric(key(current($clauses)))) {
			$clauses = current($clause);
		}
		
		$sql = array();
		$params = array();

		foreach($clauses as $clause) {
			if(!isset($clause['type'])) {
				throw new Exception('Each clause must have a type defined. Only got these keys: ' . json_encode(array_keys($clause)));
			}
		
			if(is_array($clause['value']) && is_array(current($clause['value'])) && is_numeric(key(current($clause['value'])))) {
				$clause['value'] = current($clause['value']);
			}
			
			switch($clause['type']) {
				case 'and':
				case 'or':
					if(!isset($clause['value'])) {
						throw new Exception('Must have a value for an and/or clause');
					}

					$result = self::whereBuilder($clause['value']);
					$params = array_merge($params, $result->params);
					$sql[] = '(' . implode(' ' . $clause['type'] . ' ', $result->sql) . ')';
				break;
				
				case '=':
				case 'like':
				case '>':
				case '<':
				case '<>':
				case '!=':
				case '>=':
				case '<=':
					if(!isset($clause['field'])) {
						throw new Exception("field is required for a comparison clause");
					}
					
					if(!isset($clause['value'])) {
						throw new Exception("value is required for a comparison clause: " . json_encode($clause));
					}
					
					$sql[] = "{$clause['field']} {$clause['type']} ?";
					$params[] = typeToPrim($clause['value']);
				break;
				
				case 'is null':
					if(!isset($clause['field'])) {
						throw new Exception("field is required for a is null clause");
					}
					
					$sql[] = "{$clause['field']} is null";
				break;
				
				case 'between':
					if(!isset($clause['field'])) {
						throw new Exception("field is required for a between clause");
					}
					
					if(!isset($clause['value'])) {
						throw new Exception("value is required for a between clause");
					}
										
					$sql[] = "({$clause['field']} between ? and ?)";
					$params[] = typeToPrim($clause['value'][0]);
					$params[] = typeToPrim($clause['value'][1]);
				break;
				
				case 'in':
					if(!isset($clause['field'])) {
						throw new Exception("field is required for an in clause");
					}

					if(!isset($clause['value'])) {
						throw new Exception("value is required for an in clause");
					}
										
					$inArray = array();
					foreach($clause['value'] as $val) {
						$params[] = typeToPrim($val);
						$inArray[] = '?';
					}
					
					$sql[] = "{$clause['field']} in(" . implode(',', $inArray) . ')';
				break;
				
				default:
					throw new Exception('Unknown type :' . $clause['type'] . ' for clause: ' . json_encode($clause));
				break;
			}
		}

		return (object)array(
			'sql' => $sql,
			'params' => $params);
	}
		
	/* Return Voltron_DB_RecordSet */
	public function getWhere($clause = false, $options = array())
	{
		//build the actual sql freund
		$where = '';
		$params = array();
		$custom = '';
		
		if($clause) {
			if($clause['type'] == 'rawsql') {
				$custom = $clause['field'];
			} else {
				$clause = self::whereBuilder($clause); 
				$where = 'where ' . current($clause->sql);
				$params = $clause->params;
			}
		}
		
		$query = 'select ' . (isset($options['page']) ? 'SQL_CALC_FOUND_ROWS' : '') . ' *' 
                           . (isset($options['groupBy']) ? ', count(*) as groupByTotal' : '') 
                           . " from `{$this->table}` {$custom} " 
			. $where
			. (isset($options['groupBy']) ? ' group by ' . ($options['groupBy']) : '')
			. (isset($options['orderBy']) ? ' order by ' . ($options['orderBy'] . (isset($options['orderDir']) ? ' ' . $options['orderDir'] : '')) : '')
			. (isset($options['limit']) ? ' limit ' . ((int)$options['limit']) : '')
			. (isset($options['page']) ? ' limit ' . ((int)$options['page'] * (int)$options['perPage']) . ', ' . ((int)$options['perPage']) : '');

		$result = Voltron_Registry::get('dbh')->query($this, $query, $params);
						
		if(isset($options['page'])) {
			$totalLimited = $result->count();
			$result = newObject('Voltron_DB_PaginatedResult')
				->setPage($options['page'])
				->setTotal(Voltron_Registry::get('dbh')->totalRows())
				->setResults($result);
		}
			
		return $result;;
	}
	
	public static function composeWhere($argList)
	{
		$values = array();
		if(!is_numeric(key($argList))) {
			foreach($argList as $k => $v) {
				$values[] = W('=', $k, $v);
			}
		} else {
			while(!empty($argList)) {
				$values[] = W('=', array_shift($argList), array_shift($argList));
			}
		}
		
		return W('and', $values);
	}
	
	public function getWhereFields()
	{
		$args = func_get_args();
		$options = array();
		if(count($args) % 2) {
			$options = array_pop($args);
			if(!is_array($options)) {
				throw new Exception('Call to getWhereFields but w/ an unbalanced number of arguments expects an even set of fields and values');
			}
		}
		
		return $this->getWhere(self::composeWhere($args), $options);
	}
	
	public function dispatchRecord($data = false) 
	{
		return newObject($this->getRecordClassName(), $data)->setModelName(get_class($this));
	}
	
	/* Return Voltron_Model_Record [extended] */
	public function getOne()
	{	
		$args = func_get_args();
		if(count($args) == 1 && is_object($args[0])) {
			$clause = array_shift($args);
		} else {
			$clause = self::composeWhere($args);
		}
		
		$result = $this->getWhere($clause);
		return $result->count() ? $result->current() : false;
	}
	
	public function getAll()
	{
		return $this->getWhere();
	}
	
	public function getByField($field, $value)
	{
		return $this->getWhere(W('and', W('=', $field, $value)));
	}
	
	public function get($id)
	{
		return $this->getOne($this->primaryKey, $id);
	}
	
	public function getSchema($dbType = 'MYSQL')
	{
		if($dbType != 'MYSQL') {
			throw new Exception('In theory we should support other databases, currently we do not');
		}
		
		$fields = array();
		foreach($this->getFields() as $field) {
			if($type = Type::$mysqlType[$field->class]) {
				if($field->class == Type::Enum) {
					$enumTypes = array();
					foreach(current($field->spec) as $enumType) {
						$enumTypes[] = "'{$enumType}'";
					}
					$type .= '(' . implode(', ', $enumTypes) . ')';
				}
				
				if($field->class == Type::Primary) {
					$extra = ' auto_increment';
				} else {
					$extra = '';
				}
				
				$fields[]  = "\t`{$field->name}` $type NOT NULL{$extra}";

				if($field->class == Type::Primary) {
					$fields[] = "\tPRIMARY KEY (`{$field->name}`)";
				}
			}
		}

		return "DROP TABLE IF EXISTS `{$this->table}`;\nSET character_set_client = utf8;\nCREATE TABLE `{$this->table}` (\n" 
			. implode(",\n", $fields) 
			. "\n) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;";
	}
}
