<?php

namespace Accounting\Object;

class Entry extends \Core\Object
{
	protected $_table = 'Entry';

	public function fields()
	{
		$fields = [
			'name' => [
				'type' => 'varchar',
				'default' => '',
				'null' => false,
			],
			'user_id' => [
				'type' => 'int',
				'default' => null,
				'null' => false,
			],
			'category_id' => [
				'type' => 'int',
				'default' => null,
				'null' => false,
			],
			'date' => [
				'type' => 'date',
				'default' => date('Y-m-d'),
				'null' => false,
			],
			'sum' => [
				'type' => 'float',
				'default' => 0,
				'null' => false,
			],
		];

		return array_merge($fields, parent::fields());
	}

	public function relations()
	{
		return [
			'user' => [
				'field' => 'user_id',
				'class' => 'User'
			],
			'category' => [
				'field' => 'category_id',
				'class' => 'Category'
			]
		];
	}
}
