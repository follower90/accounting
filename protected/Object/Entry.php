<?php

namespace Accounting\Object;

class Entry extends \Core\Object
{
	protected static $_config;

	public function getConfig()
	{
		if (empty(self::$_config)) {
			self::$_config = clone parent::getConfig();
			self::$_config->setTable('Entry');
			self::$_config->setFields([
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
			]);
		}

		\Core\Orm::registerRelation(
			['type' => 'simple', 'alias' => 'user'],
			['class' => 'Entry', 'field' => 'user_id'],
			['class' => 'User']
		);

		\Core\Orm::registerRelation(
			['type' => 'simple', 'alias' => 'category'],
			['class' => 'Entry', 'field' => 'category_id'],
			['class' => 'Category']
		);

		return self::$_config;
	}
}
