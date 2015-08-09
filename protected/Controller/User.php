<?php

namespace Accounting\Controller;

use Core\Authorize;
use Core\Router;

class User extends Base
{
	public function methodLogin()
	{
		$this->authorize->login($this->request('name'), $this->request('pass'),
			function($password) {
				return \Accounting\Object\User::hashPassword($password);
			}, true
		);

		Router::redirect('/');
	}
	
	public function methodLogout()
	{
		$this->authorize->logout();
		Router::redirect('/');
	}
}
