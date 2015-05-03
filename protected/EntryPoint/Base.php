<?php

namespace Accounting\EntryPoint;

use Accounting\Routes\Client as Routes;
use Core\EntryPoint;
use Core\Config;
use Core\App;

class Base extends EntryPoint
{
	public function getType()
	{
		return 'Controller';
	}

	public function init()
	{
		Config::set('site.language', 'ru');
		Routes::register();

		$app = new App($this);
		$app->run();
	}
}
