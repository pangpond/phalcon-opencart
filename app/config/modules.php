<?php 
$application = new \Phalcon\Mvc\Application($di);

 // Register the installed modules
$application->registerModules(
    array(
        'settings'  => array(
            'className' => 'Modules\Settings\Module',
            'path'      => __DIR__ . '/../modules/settings/Module.php',
        ),
        'library'  => array(
            'className' => 'Modules\Library\Module',
            'path'      => __DIR__ . '/../modules/library/Module.php',
        ),
        'backoffice'  => array(
            'className' => 'Modules\Backoffice\Module',
            'path'      => __DIR__ . '/../modules/backoffice/Module.php',
        ),
        'tools'  => array(
            'className' => 'Modules\Tools\Module',
            'path'      => __DIR__ . '/../modules/tools/Module.php',
        )
    )
);