<?php

namespace Accounting\Api;

use Core\Orm;
use Core\App;
use Core\Api as Api;

class Category extends Api
{
	public function __construct()
	{
		if (!$this->user = App::getUser()) {
			throw new \Exception('Not authorized');
		}
	}

	public function methodList()
	{
		return Orm::find('Category', ['user.User'], [$this->user->getId()])->getData();
	}
}
