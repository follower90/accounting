<?php

namespace Accounting\Controller;

use Core\Controller;
use Core\Authorize;
use Core\App;

class Base extends Controller
{
	protected $authorize;
	protected $user = false;

	public function __construct()
	{
		$this->authorize = new Authorize('User');
		$this->user = $this->authorize->getUser();

		parent::__construct();
	}

	public function render($data = [])
	{
		$data['title'] = 'Моя бухгалтерия 2.0';

		if ($this->user) {
			$data['usermenu'] = $this->view->render('user/authorized.phtml', ['name' => $this->user->getValue('name')]);
		} else {
			$data['usermenu'] = $this->view->render('user/login.phtml');
		}

		return $this->view->render('layout.phtml', $data);
	}
}
