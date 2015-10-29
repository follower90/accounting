<?php

namespace Accounting\Controller;

use Core\Router;

class Error extends Base
{
	public function methodIndex()
	{
		Router::sendHeaders([Router::NOT_FOUND_404]);

		$vars = ['title' => 'Page not found'];
		return $this->view->render('public/templates/404.phtml', $vars);
	}
}
