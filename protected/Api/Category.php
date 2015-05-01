<?php

namespace Category\Api;

use Core\Orm;
use Core\Api as Api;

class Category extends Api
{

	public function methodList()
	{
		/*
				if ($id = $this->logined()) {
			$data = $this->db->rows("SELECT c.* FROM `Category` c
								 RIGHT JOIN `user_categories` uc ON c.`id` = uc.`category_id`
								 WHERE uc.`user_id`=?
								 ORDER BY c.`name`", array($id));
			return json_encode(array('data' => $data));
		}

		return json_encode(array('error' => false));*/
	}
}
