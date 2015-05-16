<?php

namespace Accounting\Api;

use Core\Orm;
use Core\Authorize;
use Core\Api as Api;

class Category extends Api
{

	public function __construct()
	{
		$this->authorizer = new Authorize('User');
		$this->user = $this->authorizer->getUser();
	}

	public function methodList()
	{
		if ($this->user) {
			return Orm::find('Category', ['user.User'], [$this->user->getId()])->getData();
		} else {
			return Orm::find('Category')->getData();
		}
	}
}
