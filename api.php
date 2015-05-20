<?php

require_once('vendor/autoload.php');
require_once('config.php');

$project = new \Accounting\EntryPoint\Api();
$project->init();