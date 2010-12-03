<?php

class Voltron_Model_Type_Array extends Voltron_Model_Type_Abstract implements ArrayAccess, Iterator, Countable
{
	protected $className = false;
	
	public function __construct($value = array(), $className = false)
	{
		parent::__construct(is_array($value) ? $value : unserialize($value));
		$this->className = $className;
	}
	
	public function setClass($className) 
	{
		$this->className = $className;
		return $this;
	}
	
	private function cast($element)
	{
		if($this->className == 'Voltron_Model_Type_Primitive') {
			return $element;
		}
		
		if($element instanceof Voltron_Model_Type_Array) {
			return $element;
		}
		
		if(is_array($element)) {
			$element = (object)$element; 
		}

		if(isset($element->__class)) {
			$this->className = $element->__class;
			unset($element->__class);
		}
		 
		return $this->className ? ($element instanceof $this->className ? $element : newObject($this->className, $element)) : $element;
	}

	public function at($index)
	{
		return $this->cast($this->value[$index]);
	}
	
	public static function getFunction($funcString)
	{
		return !is_callable($funcString) ? 
			($funcString instanceof Voltron_FluentLambda ? $funcString->getLambda() : create_function('$key, $val', 'return ' . $funcString . ';'))
			: 
			$funcString;
	}
		
	public function map($funcString)
	{
		$func = self::getFunction($funcString);
		
		foreach($this->value as $key => $val) {			
			$this->value[$key] = ($val instanceof Voltron_Model_Type_Array && $this->className != 'Voltron_Model_Type_Array') ? 
				$val->map($funcString) 
				: 
				$func(newType('String', $key), $this->cast($val));
		}
		
		return $this;
	}

	public function keyByField($field) 
	{
		$array = array();
		foreach($this->value as $val) {
			$array[typeToPrim($val[$field])] = $val;
		}

		return newType(VArray, $array)->setClass($this->className);
	}
	
	public function filter($funcString)
	{
		$func = self::getFunction($funcString);
		$new = new Voltron_Model_Type_Array(array(), $this->className);
		
		foreach($this->value as $key => $val) {
			if($func(newType('String', $key), $this->cast($val))) {
				$new[$key] = ($val instanceof Voltron_Model_Type_Array && $this->className != 'Voltron_Model_Type_Array') ? 
					$val->filter($funcString) 
					: 
					$val;
			}
		}
		
		return $new;
	}
	
	public static function getArray($array)
	{
		return $array instanceof Voltron_Model_Type_Array ? $array->value() : $array;
	}
	
	public function merge($array)
	{
		return new Voltron_Model_Type_Array(array_merge($this->value, self::getArray($array)), $this->className);
	}
	
	public function flatten()
	{
		$new = new Voltron_Model_Type_Array(array(), $this->className);
		foreach($this->value as $node) {
			if(is_scalar($node)) {
				$new->push($node);
			} else {
				$new = $new->merge($node);
			}
		}
		return $new;
	}
	
	public function reduce($funcString, $initial = 1)
	{
		return array_reduce($this->value, self::getFunction($funcString), primToType($initial));
	}
	
	public function expand($funcString, $stop = 99)
	{
		$func = self::getFunction($funcString);
		$x = $this->first();
		while($this->count() < $stop) {
			$y = $this->next();
			$this->push($func($x, $y));
			$x = $y;
		}
		return $this;
	}
	
	public function diff($array)
	{
		return newObject('Voltron_Model_Type_Array', array_diff($this->value, self::getArray($array)))->setClass($this->className);
	}
	
    public function isEmpty()
    {
        return empty($this->value);
    }

    public function implode($by=',')
	{
		return newType(VString, implode($by, $this->value));
	}
	
	public function join($by=', ')
	{
		return $this->implode($by);
	}
	
	public function shift()
	{
		return $this->cast(array_shift($this->value));
	}

	public function pop()
	{
		return $this->cast(array_pop($this->value));
	}
	
    public function push($item) 
    {
        $this->value[] = $item;
        return $this;
    }

    public function toArray()
    {
        return $this->value;
    }


	public function toPrimitive() 
	{
		return typeToPrim($this->value);
	}

    public function offsetExists($offset)
    {
        return isset($this->value[$offset]);
    }

    public function offsetSet($offset, $value)
    {
        if(is_null($offset)) {
            $this->push($value);
        } else {
            $this->value[$offset] = $value;
        }
    }

	public function set($key, $value)
	{
		$this->offsetSet($key, $value);
		return $this;
	}
	
    public function offsetGet($offset)
    {
        return $this->value[$offset];
    }

    public function offsetUnset($offset)
    {
        unset($this->value[$offset]);
    }

    public function current()
    {
        return $this->cast(current($this->value));
    }

	public function first()
	{
		return $this->cast(reset($this->value));
	}
	
	public function last()
	{
		return $this->cast(end($this->value));
	}
	
    public function key()
    {
        return key($this->value);
    }

    public function next()
    {
        return $this->cast(next($this->value));
    }

    public function rewind()
    {
        reset($this->value);
    }

    public function valid()
    {
       return key($this->value) !== null;
    }

    public function count()
    {
        return count($this->value);
    }		

	public function contains($val)
	{
		return in_array($val, $this->value);
	}
	
	public function __toString()
	{
		return json_encode($this->value);
	}
	
	public function asJson()
	{
		return $this->value;
	}
	
	public function value()
	{
		return serialize($this->value);
	}
}
