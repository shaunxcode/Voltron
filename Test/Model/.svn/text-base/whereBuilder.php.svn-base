<?php 
require_once '../../../../bootstrap.php';

function W() {
	$args = func_get_args();
	$operator = strtolower(array_shift($args));
	
	return in_array($operator, array('and', 'or')) ? 
		array('type' => $operator, 'value' => $args) 
		:
		array('type' => $operator, 'field' => array_shift($args), 'value' => in_array($operator, array('in', 'between')) ? $args : array_shift($args));
}

/*
var_dump(Voltron_Model::whereBuilder(
	array(
		'type' => 'and', 
		'value' => 
			array(
				array(
					'type' => 'like',  
					'field' => 'x', 
					'value' => 'peter'),
				array(
					'type' => '=', 
					'field' => 'y', 
					'value' => 'walter'),
				array(
					'type' => 'or', 
					'value' => 
						array(
							array(
								'type' => '=', 
								'field' => 'z',
								'value' => 'quadrangle'),
							array(
								'type' => 'between',
								'field' => 'y',
								'value' => array(600, 700)))),
				array(
					'type' => 'in', 
					'field' => 'name', 
					'value' => array('shaun', 'sammy', 'peter'))))));
*/

$ands = array(
	W('like', 'x', 'peter'), 
	W('=', 'y', 'walter'), 
	W('or', 
		W('=', 'z', 'quadrangle'), 
		W('between', 'z', array(1,2)),
		W('between', 'y', 600, 700)), 
	W('in', 'name', 'shaun', 'sammy', 'peter'),
	W('in', 'age', array(20,30,40)));

var_dump(Voltron_Model::whereBuilder(W('and', $ands)));