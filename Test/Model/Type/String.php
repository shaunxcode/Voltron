<?php
require_once '../../../../../bootstrap.php';

Voltron_Test::assert(
	'sub to right of . should be poo', 
	S('abbra.poo')->subRightOfChar('.') == '.poo');
	
puts(S('.poo')->in(array('a','.poo','b','c','d')) ? 'IN' : 'NOT IN');