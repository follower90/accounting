<?php

namespace Accounting\EntryPoint;

use Accounting\Routes\Client as Routes;
use Core\EntryPoint;
use Core\Config;
use Core\App;

class Base extends EntryPoint
{
	public function init()
	{
		Config::set('site.language', 'ru');
		Routes::register();

		$this->setLib('\Accounting\Controller');

		$app = new App($this);
		$app->run();
	}
}
