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
		return json_encode($data);
	}
}
