<?php

class LogoutController extends BaseController
{

	protected $params;

	function __construct($registry, $params)
	{
		$this->registry = $registry;
		parent::__construct($registry, $params);
	}

	public function indexAction()
	{
		$_SESSION['user_id'] = '';
		$_SESSION['name'] = '';
		setcookie('userid', '0', time() + 2592000);

		header("Location: / ");
	}
}
