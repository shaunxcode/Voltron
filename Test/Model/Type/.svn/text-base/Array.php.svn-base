<?php
require_once '../../../../../bootstrap.php';

/*
$obj = newObject('BR_Model_User_ContactSearch')->create(new Voltron_Model_Record(array(
	'name' => 'test', 
	'user_id' => 1,
	'position' => 1,
	'configuration' => newArray(VString, array('a' => 'b', 'c' => 'd'))
)));

$obj->set('configuration', $obj->configuration->set('peter', 'penis')->set('walter', array(122,23,24,array(5,6,67))));


$obj->save();
*/
echo newObject('BR_Model_User_ContactSearch')->get(1)->configuration->asJson;
echo "\n";

die();

$noprimes = newRange(2, 8)->map(N(VRange, L(y)->times(2), 50, y))->flatten;
$primes = newRange(2,50)->diff($noprimes);

Voltron_Test::assert(
	"Check primes up to 50",
	$primes->join == '2,3,5,7,11,13,17,19,23,29,31,37,41,43,47'); 

Voltron_Test::assert(
	'a is b', 
	'b' == 'a'); 

$people = newArray(VString, array(
	'red' => newArray(VGeneric, array(
		array('name' => 'peter', 'age' => 30),
		array('name' => 'sam', 'age' => 40))),
	'blue' => newArray(VGeneric, array(
		array('name' => 'walter', 'age' => 20),
		array('name' => 'eric', 'age' => 60)))));

puts($people);
puts($people->map(L(val)->map(L(val)->name)->join(','))->join("\n"));

