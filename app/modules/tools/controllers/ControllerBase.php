<?php

namespace Modules\Tools\Controllers;

use Phalcon\Mvc\Controller,
    Phalcon\Translate\Adapter\NativeArray,
    Phalcon\Tag,
    Phalcon\Mvc\Url;

class ControllerBase extends Controller
{
    protected $mainTranslate;
    protected $controllerTranslate;

    protected function _getTransPath()
    {
        global $config;
        
        $translationPath = $config->application->messageDir;
        $language = $this->session->get("language");
        if (!$language) {
            $this->session->set("language", "en");
        }
        if ($language === 'th' || $language === 'en') {
            return $translationPath.$language;
        } else {
            return $translationPath.'en';
        }
    }
    
    /**
     * Loads a translation for the whole site
     */
    public function loadMainTrans()
    {
        $translationPath = $this->_getTransPath();
        require $translationPath."/main.php";

        //Return a translation object
        $mainTranslate = new NativeArray(array(
            "content" => $messages
        ));

        $this->mainTranslate = $messages;


        //Set $mt as main translation object
        $this->view->setVar("mt", $mainTranslate);

      }

      /**
       * Loads a translation for the active controller
       */
    public function loadCustomTrans($transFile)
    {
        $translationPath = $this->_getTransPath();
        require $translationPath.'/'.$transFile.'.php';

        //Return a translation object
        $controllerTranslate = new NativeArray(array(
            "content" => $messages
        ));

        $this->controllerTranslate = $messages;
        // $this->translate = array_merge($this->translate, $controllerTranslate);

        //Set $t as controller's translation object
        $this->view->setVar("t", $controllerTranslate);
    }

    public function initialize()
    {
        global $config;

        Tag::prependTitle('PHP: ');
        $this->loadMainTrans();

        if ($this->session->has("auth")) {
             //Retrieve its value
            $auth = $this->session->get("auth");
            $this->view->setVar("auth", $auth);
        }

        $this->view->setVar("version", $config->application->version);
        $this->view->setVar("baseUri", $config->application->baseUri);

        if(empty($this->controllerTranslate))
            $this->controllerTranslate = array();
        $translateMessages = array_merge($this->mainTranslate, $this->controllerTranslate);
        $translate = new NativeArray(array(
            "content" => $translateMessages
        ));

        $this->crumbs->useTranslation($translate);
        $this->crumbs->setTemplate('<li><a href="{{link}}">{{label}}</a><i class="fa fa-angle-right"></i></li>', '<li><span>{{label}}</span></li>')
                ->setSeparator('  ')
                ->add('home', $config->application->baseUri, 'Home');

        $moduleName = $this->router->getModuleName();
        $controllerName = $this->dispatcher->getControllerName();
        $routerName = empty($moduleName) ? $controllerName : $moduleName;

        $activeMenu = array(
            'index' => '',
            'members' => '',
            'items' => '',
            'service' => '',
            'stats' => '',
            'report' => '',
            'settings' => '',
            'guest' => '',
            'tools' => '',
        );

        switch($routerName){
            case 'index' :
                $activeMenu['index'] = 'class="active"';
                break;

            case 'members' :
                $activeMenu['members'] = 'class="active"';
                break;

            case 'items' :
                $activeMenu['items'] = 'class="active"';
                break;

            case 'rent' :
                $activeMenu['service'] = 'class="active"';
                break;

            case 'stats' :
                $activeMenu['stats'] = 'class="active"';
                break;

            case 'report' :
                $activeMenu['report'] = 'class="active"';
                break;

            case 'settings' :
                $activeMenu['settings'] = 'class="active"';
                break;

            case 'guest' :
                $activeMenu['guest'] = 'class="active"';
                break;

            case 'tools' :
                $activeMenu['tools'] = 'class="active"';
                break;
        }

        $this->view->setVar("activeMenu", $activeMenu);
    }

    protected function forwardModule($uri){
        $uriParts = explode('/', $uri);
        return $this->dispatcher->forward(
            array(
                'module' => $uriParts[0], 
                'controller' => $uriParts[1], 
                'action' => $uriParts[2]
            )
        );
    }

    protected function forward($uri){
        $uriParts = explode('/', $uri);
        return $this->dispatcher->forward(
            array(
                'controller' => $uriParts[0], 
                'action' => $uriParts[1]
            )
        );
    }

    protected function redirect($nameRoute){

        $response = new \Phalcon\Http\Response();

        return $response->redirect(array(
            "for" => $nameRoute,
        ));
    }

    

}