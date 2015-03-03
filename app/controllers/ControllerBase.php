<?php
namespace Phoenix\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Tag;
use Phalcon\Translate\Adapter\NativeArray;

use Phoenix\Models\UsersMeta;

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

use Phalcon\Logger\Adapter\File as FileAdapter;


class ControllerBase extends Controller
{
    protected $mainTranslate;
    protected $controllerTranslate;
    protected $applicationConfig;
    protected $defaultStatus = 1;
    protected $defaultIndexLimit = 30;
    protected $defaultPageLimit = 10;
    protected $defaultNullData = 'N/A';
    protected $defaultRegisteredDate = '0000-00-00 00:00:00';
    protected $defaultCurrency = 'THB';
    protected $uploadBaseLocation = 'uploads/';
    protected $defaultLanguage = 'th';
    protected $defaultPeopleImage = 'img/teacher-man.png';
    protected $logger;
    protected $auth;

    protected function _getTransPath()
    {
        global $config;
        
        $translationPath = $config->application->messageDir;
        $language = $this->session->get("language");

        if (!$language) {
            $this->session->set("language", $this->defaultLanguage);
        }
        if ($language === 'th' || $language === 'en' || $language === 'cn') {
            return $translationPath.$language;
        } else {
            return $translationPath.$this->defaultLanguage;
        }
    }
    
    /**
     * Loads a translation for the whole site
     */
    public function loadMainTrans(){

        $translationPath = $this->_getTransPath();
        unset($messages);
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

        $this->applicationConfig = $config->application;

        $this->logger = new FileAdapter($this->applicationConfig->logsDir . "access.log");
        $this->clientIP = $this->elements->getClientIP();
        Tag::prependTitle('Super S.A.A.T. : ');
        $this->loadMainTrans();

        if ($this->session->has("auth")) {
             //Retrieve its value
            $auth = $this->session->get("auth");
            $this->view->setVar("auth", $auth);
            $this->auth = $auth;
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

        if ($this->session->has("language")) 
            $language = $this->session->get('language');
        else 
            $language = $this->defaultLanguage;

        $this->datetime->setLocale($language);

        $this->view->setVar("date", $this->datetime->Date(time()));
        $this->view->setVar("weekday", $this->datetime->Weekday(time()));

        $moduleName = $this->router->getModuleName();
        $controllerName = $this->dispatcher->getControllerName();
        $routerName = empty($moduleName) ? $controllerName : $moduleName;

        $activeMenu = array(
            'index' => '',
            'members' => '',
            'academy' => '',
            'service' => '',
            'stats' => '',
            'report' => '',
            'settings' => '',
            'guest' => '',
            'tools' => '',
            'history' => '',
            'visited' => '',
            'help' => '',
            'admin' => '',
        );

        switch($routerName){
            case 'index' :
                $activeMenu['index'] = 'class="active"';
                break;

            case 'members' :
                $activeMenu['members'] = 'class="active"';
                break;

            case 'academy' :
                $activeMenu['academy'] = 'class="active"';
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

            case 'history' :
                $activeMenu['history'] = 'class="active"';
                break;

            case 'visited' :
                $activeMenu['visited'] = 'class="active"';
                break;

            case 'help' :
                $activeMenu['help'] = 'class="active"';
                break;

            case 'admin' :
                $activeMenu['admin'] = 'class="active"';
                break;
        }

        $this->view->setVar("activeMenu", $activeMenu);

        $auth = $this->session->get('auth');

        if($auth){
            $member_id = $auth['id'];
            $memberMeta = UsersMeta::findFirst("user_id = " . $member_id . " and meta_key = 'image'");

            if($memberMeta)
                $memberImage = $this->uploadBaseLocation . 'users/' . $memberMeta->meta_value;
            else
                $memberImage = $this->defaultPeopleImage;

            if($language == 'en'){
                $currentLanguage = Tag::image("img/demo/flags/us.gif") . '<span>US</span>';
                $this->session->set("language", "en");
            }
            elseif($language == 'th'){
                $currentLanguage = Tag::image("img/demo/flags/th.gif") . '<span>TH</span>';
                $this->session->set("language", "th");
            }
            elseif($language == 'cn'){
                $currentLanguage = Tag::image("img/demo/flags/cn.gif") . '<span>CN</span>';
                $this->session->set("language", "cn");
            }

        }else{
            $memberImage = $this->defaultPeopleImage;
            $currentLanguage = Tag::image("img/demo/flags/us.gif") . '<span>US</span>';
        }

        $this->view->setVar("memberImage", $memberImage);
        $this->view->setVar("currentLanguage", $currentLanguage);
        $this->view->setVar("language", $language);

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