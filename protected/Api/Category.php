<?php

namespace Accounting\Api;

use Core\Orm;
use Core\App;
use Core\Api as Api;
use Core\Router;

class Category extends Api
{
	public function __construct()
	{
		if (!$this->user = App::getUser()) {
			Api::notAuthorized();
		}
	}

	public function methodList()
	{
		return Orm::find('Category', ['user.User'], [$this->user->getId()])->getData();
	}
}
