<?php

namespace Accounting\EntryPoint;

use Accounting\Routes\Api as Routes;
use Core\EntryPoint;
use Core\Config;
use Core\App;

class Api extends EntryPoint
{
	public function getType()
	{
		return 'Api';
	}

	public function init()
	{
		Config::set('site.language', 'ru');
		Routes::register();

		$app = new App($this);
		$app->run();
	}
}
