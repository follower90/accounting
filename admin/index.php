<?php

require_once('../vendor/autoload.php');
require_once('../config.php');

require_once('utils/aliases.php');

$project = new \Admin\EntryPoint\Base();
$project->init();
