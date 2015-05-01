<?php

namespace Accounting\Object;

class User extends \Core\Object\User
{
	protected $_table = 'User';

	public function fields()
	{
		$fields = [
			'name' => [
				'type' => 'varchar',
				'default' => '',
				'null' => false,
			],
		];

		return array_merge($fields, parent::fields());
	}
}
