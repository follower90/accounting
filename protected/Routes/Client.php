<?php

namespace Accounting\Routes;

use Core\Router;

class Client
{
	public static function register()
	{
		Router::register(['/', 'get'], 'Index', 'index', []);

		Router::register(['/add', 'post'], 'Index', 'newEntry', []);
		
		Router::register(['/login', 'post'], 'User', 'login', []);
		Router::register(['/logout', 'get'], 'User', 'logout', []);

		Router::register(['/profile', 'get'], 'Profile', 'index', []);
		Router::register(['/profile', 'post'], 'Profile', 'save', []);

		Router::register(['/example/+', 'get'], 'Example', 'index', []);
	}
}
