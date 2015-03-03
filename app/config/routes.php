<?php

$router = new Phalcon\Mvc\Router();

// $router->add("/news/{year:[0-9]+}/{month:[0-9]+}/{title:[a-zA-Z0-9\-]+}", array(
//     'controller' => 'news',
//     'action' => 'show'
// ));

// $router->add("/news/([0-9]{4})", array(
//     'controller' => 'news',
//     'action' => 'showYear',
//     'year' => 1
// ));

// $router->setDefaultModule("settings");

//Set 404 paths
// $router->notFound(array(
//     "controller" => "error",
//     "action" => "show404"
// ));


$router->add("/set-language/{language:[a-z]+}", array(
    'controller' => 'index',
    'action' => 'setLanguage'
));

$router->add('/settings/', array(
    'module' => "settings",
    'controller' => "index",
    'action' => "index"
))->setName('setting-index');

$router->add('/settings', array(
    'module' => "settings",
    'controller' => "index",
    'action' => "index"
))->setName('setting-index');

$router->add('/settings/:controller', array(
    'module' => "settings",
    'controller' => 1,
    'action' => "index"
))->setName('settings-controller');

$router->add('/settings/:controller/:action/:params', array(
    'module' => "settings",
    'controller' => 1,
    'action' => 2,
    'params' => 3
))->setName('settings-full');


$router->add('/tools/', array(
    'module' => "tools",
    'controller' => "index",
    'action' => "index"
))->setName('setting-index');

$router->add('/tools', array(
    'module' => "tools",
    'controller' => "index",
    'action' => "index"
))->setName('setting-index');

$router->add('/tools/:controller', array(
    'module' => "tools",
    'controller' => 1,
    'action' => "index"
))->setName('tools-controller');

$router->add('/tools/:controller/:action/:params', array(
    'module' => "tools",
    'controller' => 1,
    'action' => 2,
    'params' => 3
))->setName('tools-full');



$router->add('/settings/persontype', array(
    'module' => "settings",
    'controller' => "combpersontype",
))->setName('setting-persontype');

$router->add('/settings/persontype/:action/:params', array(
    'module' => "settings",
    'controller' => "combpersontype",
    'action' => 1,
    'params' => 2
))->setName('setting-persontype-full');

// $router->add('/settings/itemstype', array(
//     'module' => "settings",
//     'controller' => "items_type",
// ))->setName('setting-items_type');

// $router->add('/settings/itemstype/:action/:params', array(
//     'module' => "settings",
//     'controller' => "items_type",
//     'action' => 1,
//     'params' => 2
// ))->setName('setting-items_type-full');


$router->add('/backoffice/', array(
    'module' => "backoffice",
    'controller' => "index",
    'action' => "index"
))->setName('backoffice-index');

$router->add('/backoffice', array(
    'module' => "backoffice",
    'controller' => "index",
    'action' => "index"
))->setName('backoffice-index');

$router->add('/backoffice/:controller', array(
    'module' => "backoffice",
    'controller' => 1,
    'action' => "index"
))->setName('backoffice-controller');

$router->add('/backoffice/:controller/:action/:params', array(
    'module' => "backoffice",
    'controller' => 1,
    'action' => 2,
    'params' => 3
))->setName('backoffice-full');



// $router->add("/guest", array(
//     'controller' => 'index',
//     'action' => 'guest'
// ));
// $router->add("/guest/", array(
//     'controller' => 'index',
//     'action' => 'guest'
// ));

//Remove trailing slashes automatically
$router->removeExtraSlashes(true);
