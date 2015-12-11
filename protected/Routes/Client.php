<?php

namespace Accounting\Routes;

use Core\Router;

class Client
{
	public static function register()
	{
		$router = new Router();
		$router
			->route('POST', '/add', 'index#newEntry')

			->route('POST', '/login', 'user#login')
			->route('GET', '/logout', 'user#logout')

			->route('GET', '/profile', 'profile#index')
			->route('POST', '/profile', 'profile#save')
			->route('GET', '/test/:id/action/:action', 'index#test');
	}
}
