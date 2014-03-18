<?php
use ScrumMastor\ScrumMastorApplication;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = new ScrumMastorApplication(array('env' => 'test'));
return $app;


