<?php

require_once('vendor/autoload.php');
require_once('setup.php');

$project = new \Accounting\EntryPoint\Api();
$project->init();