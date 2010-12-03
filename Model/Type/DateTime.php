<?php

namespace Voltron\Model\Type;

class DateTime extends Base 
{
	private $format; 
	
	public static $parts = array(
		'year' => 'Y',
		'years' => 'Y',
		'month' => 'm',
		'months' => 'm',
		'day' => 'd',
		'days' => 'd',
		'hour' => 'H',
		'hours' => 'H',
		'minute' => 'i',
		'minutes' => 'i',
		'second' => 's',
		'seconds' => 's');
		
	public function __construct($date = false, $format = 'Y-m-d H:i:s')
	{
		$this->format = $format;
		$this->value = $date ? DateTime::createFromFormat($format, $date) : new DateTime();
	}
	
	public function copy() 
	{
		return self::createFromString($this->value());
	}
	
	public static function createFromString($string)
	{
		if(!$timestamp = strtotime($string)) {
			return false;
		}

		return newType('DateTime')->setValue(newObject('DateTime')->setTimestamp($timestamp));		
	}
	
	public function subDays($days)
	{
		$this->value->modify("- {$days} day");
		return $this;
	}
	
	public function addDays($days)
	{
		$this->value->modify("+ {$days} day");
		return $this;
	}
	
	public function addMonths($months)
	{
		$this->value->modify("+ {$months} month");
		return $this;
	}
	
	public function addYears($years)
	{
		$this->value->modify("+ {$years} year");
		return $this;
	}
	
	public function setFormat($format)
	{
		$this->format = $format;
		return $this;
	}
	
	public function __toString()
	{
		return $this->formatAs($this->format);
	}
	
	public function formatAs($format)
	{
		return $this->value->format($format);
	}
	
	public function value()
	{
		return $this->__toString();
	}
	
	public function asTimestamp()
	{
		return newType('Timestamp', $this->value->getTimestamp());
	}
	
	public function __get($part)
	{
		if(isset(self::$parts[$part])) {
			return $this->value->format(self::$parts[$part]);
		} else {
			if($val = parent::__get($part)) {
				return $val;
			}
			throw new Exception("$part does not exist for date");
		}
	}
}
