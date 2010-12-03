<?php

namespace Voltron;

class Type
{
    const Primary = 'Primary';
    const Integer = 'Integer';
    const BigInt = 'BigInt';
    const Enum = 'Enum';
    const Set = 'Set';
    const DateTime = 'DateTime';
    const Hour = 'Hour';
    const Minute = 'Minute';
    const Second = 'Second';
    const Year = 'Year';
	const Time = 'Time';
	const TimeStamp = 'Timestamp';
    const Timestamp = 'Timestamp';
    const String = 'String';
    const Text = 'Text';
    const Number = 'Number';
    const Float = 'Float';
	const Decimal = 'Decimal';
    const Boolean = 'Boolean';
    const Join = 'Join';
    const JoinMany = 'JoinMany';
    const JoinPivot = 'JoinPivot';
    const JoinReverse = 'JoinReverse';
    const Email = 'Email';
    const Password = 'Password';
	const MD5Password = 'MD5Password';
	const Calculated = 'Calculated';
	const HexColor = 'HexColor';

    public static $mysqlType = array(
	'Primary' => 'bigint(20)',
    	'Integer' => 'int',
    	'BigInt' => 'bigint(20)',
    	'Enum' => 'ENUM',
    	'Set' => 'varchar(255)',
    	'DateTime' => 'datetime',
    	'Hour' => 'int',
    	'Minute ' => 'int',
    	'Second' => 'int',
    	'Year' => 'int',
	'Time' => 'int',
	'TimeStamp' => 'timestamp',
    	'Timestamp' => 'timestamp',
    	'String' => 'varchar(255)',
    	'Text' => 'text',
    	'Number' => 'float',
    	'Float' => 'float',
	'Decimal' => 'Decimal(10,2)',
    	'Boolean' => 'tinyint(1)',
	'Email' => 'varchar(255)',
	'Password' => 'varchar(255)',
	'MD5Password' => 'varchar(255)',
	'HexColor' => 'varchar(255)',
    	'Join' => false,
    	'JoinMany' => false,
    	'JoinPivot' => false,
    	'JoinReverse' => false,
	'Calculated' => false);
}

