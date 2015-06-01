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
			]);
		}

		return self::$_config;
	}
}
