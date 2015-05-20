<?php

\Core\Config::setDb('default', [
	'host' => 'host',
	'name' => 'db',
	'user' => 'user',
	'password' => 'pass',
	'charset' => 'utf8'
]);

\Core\Config::registerProject('Accounting', 'default');