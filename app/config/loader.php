<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */

$loader->registerNamespaces(array(
    'Phoenix\Models' => $config->application->modelsDir,
    'Phoenix\Controllers' => $config->application->controllersDir,
    'Phoenix\Forms' => $config->application->formsDir,
    'Phoenix\Plugins' => $config->application->pluginsDir,
    'Phoenix\Library' => $config->application->libraryDir,
    'Phoenix\Library\PHPExcel' => $config->application->libraryDir. '/PHPExcel/Classes/PHPExcel/',
    'Phoenix' => '/'
));

// $loader->registerDirs(
//     array(
//         __DIR__ . $config->application->controllersDir,
//         __DIR__ . $config->application->pluginsDir,
//         __DIR__ . $config->application->libraryDir,
//         __DIR__ . $config->application->modelsDir,
//     )
// );


$loader->register();

// Use composer autoloader to load vendor classes
require_once __DIR__ . '/../../vendor/autoload.php';