<?php

class Voltron_AppController extends Voltron_Controller 
{
	public function __construct($name, $content = false, $routeName = false)
	{
		parent::__construct($name);
		$this->view->content = $content;
		$this->view->routeName = $routeName;
	}
}
