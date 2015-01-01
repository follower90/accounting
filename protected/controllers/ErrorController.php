<?php

class errorController extends BaseController
{
	
	protected $params;
	protected $db;
	
	function  __construct($registry, $params)
	{ 
		$this->registry = $registry;
		parent::__construct($registry, $params);
	}
	
	
	function indexAction()
	{
		header("HTTP/1.0 404 Not Found");
		$view = new View($this->registry);

		$data['content'] = $view->Render('404.phtml');
		return $this->Render($data);
	}
}
