<?php

namespace Accounting\Object;

class User extends \Core\Object\User
{
	protected static $_config;

	public function getConfig()
	{
		if (empty(self::$_config)) {
			self::$_config = clone parent::getConfig();
			self::$_config->setTable('User');
			self::$_config->setFields([
				'name' => [
					'type' => 'varchar',
					'default' => '',
					'null' => false,
				],
				'categories' => [
					'type' => 'HAS_MANY',
					'class' => 'Category'
				]
			]);
		}

		\Core\Orm::registerRelation(
			['type' => 'multiple', 'alias' => 'categories', 'table' => 'User__Category'],
			['class' => 'User'],
			['class' => 'Category']
		);

		return self::$_config;
	}

	public static function hashPassword($password)
	{
		return md5($password);
	}

	public static function desc()
	{
		return static::all()->setSorting('id', 'desc');
	}
}
