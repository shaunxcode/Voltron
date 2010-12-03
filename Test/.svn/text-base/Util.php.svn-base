<?php 

require_once 'Test.php';


class A {
	public static $x = array();
}

class B extends A {
	public static $x = array('a' => 'b', 1,2,3); 
}

class C extends B {
	public static $x = array(4,5,6, 'c' => 'd', 'a' => 'Q');
}


class Voltron_Test_Util {
	public static function run()
	{
		print_r(Voltron_Util::getExtendedStaticArray('C', 'x'));
	}	
}

Voltron_Test_Util::run();
