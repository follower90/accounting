<?php

namespace Accounting\Controller;

class Error extends Base
{
	public function methodIndex()
	{
		header("HTTP/1.0 404 Not Found");

		$vars = ['title' => 'Page not found'];
		return $this->view->render('public/templates/404.phtml', $vars);
	}
}
