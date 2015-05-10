<?php

namespace Accounting\Object;

class Category extends \Core\Object
{
	protected $_table = 'Category';

	const TYPE_EARNING = '+';
	const TYPE_EXPENSE = '-';

	public function fields()
	{
		$fields = [
			'type' => [
				'type' => 'int',
				'default' => null,
				'null' => false,
			],
			'name' => [
				'type' => 'varchar',
				'default' => '',
				'null' => false,
			],
		];

		return array_merge($fields, parent::fields());
	}

	public function relations()
	{
		$relations = [
			'user' => [
				'multiple' => true,
				'class' => 'User',
				'table' => 'User__Category'
			]
		];

		return $relations;
	}
}
