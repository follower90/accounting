<?php

namespace Accounting\Routes;

use Core\Router;

class Client
{
	public static function register()
	{
		Router::register(['/', 'get'], 'Index', 'index', []);
		Router::register(['/example/+', 'get'], 'Example', 'index', []);
	}
}
