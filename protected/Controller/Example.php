<?php

namespace Accounting\Controller;

class Example extends Base
{
	function methodIndex()
	{
		$vars = [];
		$vars['example'] = \Core\Orm::find('Example', ['url'], [$this->params('example')])->getData();

		$vars['title'] = 'Example Title';
		$data['content'] = $this->view->render('public/templates/example.phtml', $vars);

		return $this->render($data);
	}
}
