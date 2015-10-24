<?php

require_once '../bootstrap.php';
require_once '../BondIdeaApplication.php';
require_once '../vendor/autoload.php';

$debug_mode = true;
$app = new BondIdeaApplication($debug_mode);
$app->run();
