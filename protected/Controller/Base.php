<?php

namespace Accounting\Controller;

use Core\Controller;
use Core\Authorize;
use Core\App;

class Base extends Controller
{
	protected $authorizer;
	protected $user = false;

	public function __construct()
	{
		$this->authorizer = new Authorize('User');
		$this->user = $this->authorizer->getUser();

		parent::__construct();
	}

	public function render($data)
	{
		$data['title'] = 'Моя бухгалтерия 2.0';

		if ($this->user) {
			$data['usermenu'] = $this->view->render('public/templates/user/authorized.phtml', ['name' => $this->user->getValue('name')]);
		} else {
			$data['usermenu'] = $this->view->render('public/templates/user/login.phtml');
		}

		return $this->view->render('public/templates/layout.phtml', $data);
	}
}
