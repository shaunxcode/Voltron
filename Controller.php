<?php

class Voltron_Controller 
{
	public $layout = false;
	protected $name;
	protected $view;
    protected $Request;
	protected $Session;
	protected $route;
	protected $access = OPEN;
	protected $models = array();
	protected $responseType = HTML;
	
	public function __construct($name, $route = false)
	{
		$this->Request = Voltron_Registry::get('Request');
		$this->Session = Voltron_Registry::get('Session');
		
		$this->name = $name;
		$this->route = $route;
		$this->view = (object)array();
		
		foreach($this->models as $model) {
			$this->$model = newObject(APPNAME . '_Model_' . $model); 
		}
	}

	public function checkAccess($method)
	{
		if(!is_array($this->access)) {
			$level = $this->access;
			$copClass = Voltron_DefaultCop;
		} else if(is_array($this->access) && count($this->access) == 2) {
			list($level, $copClass) = $this->access;
		} else {
			throw new Exception("If you are going to set the access property of a controller as an array it expects [level, cop]");
		}
		
		$cop = new $copClass;
		
		if(!$cop->checkUserClearance(get_class($this), $method, $level)) {
			$this->redirect('NoAccess');
		}
		return true;
	}
	
	public function __dispatch($method, $args = array())
	{
		$this->checkAccess($method);
		
		//assume view is same as method unless over-ridden by method
		$this->viewName = $method;

		if(is_callable(array($this, $method))) {
			call_user_func_array(array($this, $method), $args);
		}

		if($this->responseType == JSON) {
			die(json_encode(is_object($this->view) && method_exists($this->view, 'asJson') ? $this->view->asJson() : $this->view));
		}

		if($this->responseType == IMAGE) {
			header('Content-Type: image/png');
			die($this->view->contents);
		}
		
		if($this->responseType == FILE) {
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="' . $this->view->filename . '"');
			die($this->view->contents);
		}
		
		//load view
		$view = newObject(
			APPNAME . '_View_' . $this->name . '_' . $this->viewName,
			$this->view)->render();
			
		if($this->responseType == VIEW) {
			die($view);
		}
		
		return $view;
	}
	
	public function redirect($routeName)
	{
		header('location:/' . URLBASE . '/' . $routeName);
		die();
	}
}
