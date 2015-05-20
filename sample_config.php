<?php

/*
 * Set database connection and rename this file to 'config.php'
 */

use \Core\Config;

Config::setDb('default', [
	'host' => 'host',
	'name' => 'db',
	'user' => 'user',
	'password' => 'pass',
	'charset' => 'utf8'
]);

Config::registerProject('Accounting', 'default');
Config::registerProject('Admin', 'default');
