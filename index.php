<?php

	error_reporting(E_ALL);
	ini_set('display_errors', true);
	date_default_timezone_set('Europe/Kiev');

	define('SITE_PATH', dirname(__FILE__)."/");
	define('CLASSES', dirname(__FILE__)."/protected/classes/");
	define('CONROLLERS', dirname(__FILE__)."/protected/controllers/");
	define('OBJECTS', dirname(__FILE__)."/protected/objects/");
	define('MODULES', dirname(__FILE__)."/protected/modules/");

	require_once(CLASSES."initializer.php");
