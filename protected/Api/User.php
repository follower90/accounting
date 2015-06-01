<?php

namespace Accounting\Api;

use Core\Authorize;
use Core\Api as Api;

class User extends Api
{
	public function methodAuth()
	{
		$authorize = new Authorize('User');
		$user = $authorize->getUser();

		return $user ? $user->getValues() : false;
	}

	public function methodLogin()
	{
		$authorize = new Authorize('User');
		$authorize->login($this->request('name'), $this->request('pass'),
			function($password) {
				return \Accounting\Object\User::hashPassword($password);
			}
		);

		return $authorize->getUser();
	}

	public function methodLogout()
	{
		$authorize = new Authorize('User');
		$authorize->logout();

		return true;
	}
}
