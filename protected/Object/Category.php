<?php

namespace Accounting\Object;

class Category extends \Core\Object
{
	const TYPE_EARNING = '+';
	const TYPE_EXPENSE = '-';

	protected static $_config;

	public function getConfig()
	{
		if (empty(self::$_config)) {
			self::$_config = clone parent::getConfig();
			self::$_config->setTable('Category');
			self::$_config->setFields([
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
				'user' =>[
					'type' => 'BELONGS_TO',
					'class' => 'User'
				]
			]);
		}

		\Core\Orm::registerRelation(
			['type' => 'multiple', 'alias' => 'user', 'table' => 'User__Category'],
			['class' => 'Category'],
			['class' => 'User']
		);

		return self::$_config;
	}
}
