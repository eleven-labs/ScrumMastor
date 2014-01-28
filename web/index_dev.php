<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;
use ScrumMastor\ScrumMastorApplication;

require dirname(__DIR__) . '/vendor/autoload.php';

Debug::enable();
$app = new ScrumMastorApplication(array('env' => 'dev'));
$app->run();
