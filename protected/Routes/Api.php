<?php

namespace Accounting\Routes;

use Core\Router;

class Api
{
	public static function register()
	{
		Router::register(['Category.list', 'get'], 'Category', 'list', []);
		Router::register(['User.auth', 'get'], 'User', 'auth', []);
		Router::register(['User.login', 'get'], 'User', 'login', []);
		Router::register(['User.logout', 'get'], 'User', 'logout', []);
		Router::register(['Entry.get', 'post'], 'Entry', 'get', []);
		Router::register(['Entry.save', 'post'], 'Entry', 'save', []);
		Router::register(['Entry.delete', 'post'], 'Entry', 'delete', []);
	}
}
