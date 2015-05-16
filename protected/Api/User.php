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
}
