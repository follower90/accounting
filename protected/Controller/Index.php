<?php

namespace Accounting\Controller;


class Index extends Base
{
	function methodIndex()
	{
		$vars['content'] = $this->view->render('public/templates/main.html');
		return $this->render($vars);
	}
}
