<?php
require '../vendor/autoload.php';
use Crada\Apidoc\Builder;
use Crada\Apidoc\Exception;

$classes = array(
    'ScrumMastor\Controller\TaskController',
    'ScrumMastor\Controller\TagController',
);

$output_dir = __DIR__.'/';
$output_file = 'api.html'; // defaults to index.html

try {
    $builder = new Builder($classes, $output_dir, $output_file);
    $builder->generate();
} catch (Exception $e) {
        echo 'There was an error generating the documentation: ', $e->getMessage();
        die();
}

include $output_dir.$output_file;
