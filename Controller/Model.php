<?php

namepsace Voltron\Controller;

class Model extends \Voltron\Controller
{
	public function dispatch()
	{
		$this->responseType = JSON;
		
		try {
			$model = newObject(APPNAME . '_Model_' . $this->Request->model);
			$this->view = call_user_func_array(
				array($model, $this->Request->method), 
				isset($this->Request->args->__type) ? 
					array(newObject($this->Request->args->__class, $this->Request->args->data))
					:
					(isset($this->Request->args->__class) ? 
						array($model->dispatchRecord($this->Request->args->data)) 
						:
						(array)$this->Request->args));
		} catch (Exception $e) {
			$this->view = array('exception' => true, 'message' => $e->getMessage());
		}
	}
	
	public function index()
	{
		$this->view->models = Voltron_Directory::getTree(APPROOT, 'Model')
			->filter(L(key)->isNot('Record'))
			->map(L(val)->replace('.php'));
	}
}
