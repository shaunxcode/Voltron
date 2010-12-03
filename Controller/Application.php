<?php

namespace Voltron\Controller;

class Application extends \Voltron\Controller 
{
	public function __construct($name, $content = false, $routeName = false)
	{
		parent::__construct($name);
		$this->view->content = $content;
		$this->view->routeName = $routeName;
	}
}
