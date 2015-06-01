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
				return self::passwordHash($password);
			}
		);

		Router::redirect('/');
	}
	
	public function methodLogout()
	{
		$this->authorize->logout();
		Router::redirect('/');
	}

	public static function passwordHash($password)
	{
		return md5($password);
	}
}
