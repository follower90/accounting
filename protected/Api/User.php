<?php

namespace Accounting\Api;

use Core\Authorize;
use Core\Api as Api;

class User extends Api
{
	public function methodAuth()
	{
		$this->authorizer = new Authorize('User');
		$this->user = $this->authorizer->getUser();

		return $this->user ? $this->user->getValues() : false;
	}


	public function methodLogin()
	{
		$this->authorizer = new Authorize('User');
		$this->authorizer->login($this->request('name'), $this->request('pass'),
			function($password) {
				return \Accounting\Controller\User::passwordHash($password);
			}
		);

		return $this->authorizer->getUser();
	}

	public function methodLogout()
	{
		$this->authorizer = new Authorize('User');
		$this->authorizer->logout();

		return true;
	}
}
