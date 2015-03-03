<?php

namespace Modules\Settings;

use Phalcon\Loader,
    Phalcon\Mvc\Dispatcher,
    Phalcon\Mvc\View,
    Phalcon\Mvc\ModuleDefinitionInterface;

use Phoenix\Library\Security\Security;


class Module implements ModuleDefinitionInterface
{

    /**
     * Register a specific autoloader for the module
     */
    public function registerAutoloaders()
    {

        $loader = new Loader();

        $loader->registerNamespaces(
            array(
                'Modules\Settings\Controllers' => __DIR__ . '/controllers/',
                'Modules\Settings\Models'      => __DIR__ . '/models/',
            )
        );

        $loader->register();
    }

    /**
     * Register specific services for the module
     */
    public function registerServices($di)
    {

        /**
         * We register the events manager
         */
        $di->set('dispatcher', function() use ($di) {

            $eventsManager = $di->getShared('eventsManager');

            $security = new Security($di);
            $eventsManager->attach('dispatch', $security);

            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('Modules\Settings\Controllers');
            $dispatcher->setEventsManager($eventsManager);

            return $dispatcher;
        }, true);


        //Registering the view component
        $di->set('view', function() {
            $view = new View();
            $view->setViewsDir(__DIR__ . '/views/');

            $view->setLayoutsDir('../../../views/common/layouts');
            $view->setTemplateAfter('flat');
            $view->setLayout('main');
 
            return $view;
        });
    }

}