<?php

namespace Accounting\Api;

use Core\Orm;
use Core\Api as Api;

class Category extends Api
{

	public function methodList()
	{
		$data = Orm::find('Category', ['user.User'], [1])->getData();
		$this->output($data);
	}
}
