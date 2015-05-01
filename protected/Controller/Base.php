<?php

namespace Accounting\Controller;

use Core\Controller\Controller;

class Base extends Controller
{
	function render($vars)
	{
		return $this->view->render('public/templates/layout.html', $vars);
	}
}
