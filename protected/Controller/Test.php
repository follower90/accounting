<?php

namespace Accounting\Controller;

use Core\Authorize;
use Core\Router;

class Test extends Base
{
	public function methodIndex()
	{
		return $this->view->render('public/test/index.html');
	}
}
