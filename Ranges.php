<?php

class Voltron_Ranges
{
	public static function months($start = false, $end = false)
	{
		return array(
			'1' => 'Jan',
			'2' => 'Feb',
			'3' => 'Mar',
			'4' => 'Apr',
			'5' => 'May',
			'6' => 'Jun',
			'7' => 'Jul',
			'8' => 'Aug',
			'9' => 'Sep',
			'10' => 'Oct',
			'11' => 'Nov',
			'12' => 'Dec');
	}
	
	public static function years($start = false, $end = false)
	{
		$range = range($start, $end);
		return array_combine($range, $range);
	}
	
}