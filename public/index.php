<?php
error_reporting(E_ALL);
date_default_timezone_set('Asia/Bangkok');
header('Content-Type: text/html; charset=utf-8');
try {

    /**
     * Read the configuration
     */
    $config = new \Phalcon\Config\Adapter\Ini(__DIR__ . "/../app/config/config.ini");

    /**
     * Read auto-loader
     */
    include __DIR__ . "/../app/config/loader.php";

    /**
     * Read services
     */
    include __DIR__ . "/../app/config/services.php";

    /**
     * Handle the request
     */
    include __DIR__ . "/../app/config/modules.php";
    


    echo $application->handle()->getContent();

} catch (\Exception $e) {
    echo $e->getMessage();
}
