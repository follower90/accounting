<?php

namespace Accounting\Controller;

use Core\Controller;
use Core\Authorize;
use Core\App;

class Base extends Controller
{
	protected $authorizer;

	public function __construct()
	{
		$this->authorizer = new Authorize('User');
		parent::__construct();
	}

	public function render($data)
	{
		$data['title'] = 'Моя бухгалтерия 2.0';

		if ($user = $this->authorizer->getUser()) {
			$data['usermenu'] = $this->view->render('public/templates/user/authorized.phtml', ['name' => $user->getValue('name')]);
		} else {
			$data['usermenu'] = $this->view->render('public/templates/user/login.phtml');
		}

		echo $this->view->render('public/templates/layout.phtml', $data);
	}
}
