<?php

namespace Accounting\Routes;

use Core\Router;

class Api
{
	public static function register()
	{
		Router::register(['User', 'get'], 'User', 'get', []);
	}
}
