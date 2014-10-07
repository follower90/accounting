<?php

error_reporting(E_ALL);
echo '1';
require_once 'src/config.php'
require_once 'src/router.php'
echo '2';
date_default_timezone_set('Europe/Kiev');

$app = new Router();
$app->run();