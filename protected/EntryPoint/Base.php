<?php

namespace Accounting\EntryPoint;

use Accounting\Routes\Client as Routes;
use Core\EntryPoint;
use Core\Config;
use Core\App;

class Base extends EntryPoint
{
	protected $_user;

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
		
		$this->_authorize();
	}

	protected function _authorize()
	{
		$authorizer = new \Core\Authorize();
		$this->_user = $authorizer->checkLoginState('User');
	}
}
