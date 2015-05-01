<?php

namespace Accounting\Controller;

class Error extends Base
{
	function methodIndex()
	{
		header("HTTP/1.0 404 Not Found");

		$vars = ['title' => 'Page not found'];
		$data['content'] = $this->view->render('public/templates/404.phtml', $vars);
		return $this->render($data);
	}
}