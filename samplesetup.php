<?php

// Rename this file to setup.php

\Core\Config::set('rootPath', getcwd());
\Core\Config::setProject('Accounting');
\Core\Config::setDb([
	'default' => [
		'host' => 'localhost', 
		'name' => 'dbname',
		'user' => 'user',
		'password' => 'passs',
		'charset' => 'utf8'
	]
]);
