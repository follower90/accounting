<?php

namespace Accounting\EntryPoint;

use Accounting\Routes\Api as Routes;
use Core\EntryPoint;
use Core\Config;
use Core\App;

class Api extends EntryPoint
{

	public function init()
	{
		Config::set('site.language', 'ru');
		Routes::register();

		$this->setLib('\Accounting\Api');

		$app = new App($this);

		$authorize = new \Core\Authorize('User');
		$authorize->getUser();

		$app->run();
	}

	public function debug()
	{
		if ($this->request('cmsDebug') == 'on') {
			return true;
		}

		return false;
	}

	public function output($data)
	{
		header('Content-Type: application/json');

		if ($this->debug()) {
			header('Content-Type: text/html');
		}

		return json_encode($data);
	}
}
