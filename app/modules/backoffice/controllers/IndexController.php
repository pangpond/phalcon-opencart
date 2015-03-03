<?php
namespace Modules\Backoffice\Controllers;

use Phalcon\Tag,
    Phalcon\Session as Session;

use Phoenix\Models\Settings;
use Phoenix\Models\Users;


use Phoenix\Controllers\ControllerBase;

class IndexController extends ControllerBase
{
    protected $baseBackofficeLocation = 'uploads/academy/';

    public function initialize()
    {
        // $view->setTemplateAfter('main');
        Tag::setTitle('Setting Index');
        $this->loadCustomTrans('settings/index');
        parent::initialize();

        $this->crumbs->add('settings', '', 'Backoffice'); 
        
    }

    public function indexAction()
    {

        $language = $this->session->get('language');
        //Get session info
        $auth = $this->session->get('auth');

        //Query the active user
        $user = Users::findFirst($auth['id']);
        if ($user == false || empty($auth['id'])) {
            //$this->redirectNew('index');
            $this->response->redirect('backoffice/academicyears');
        }

        $settings = Settings::find();
        if (!$settings) {
            $this->flash->error("setting was not found");

            return $this->dispatcher->forward(array(
                "controller" => "index",
                "action" => "index"
            ));
        }

            foreach ($settings as $setting) {
                $this->tag->setDefault('meta['. $setting->key . ']', $setting->value);

                 if($setting->key == 'academy_image')
                     $this->view->setVar("academyImage", $this->baseBackofficeLocation . $setting->value);
            }


        $this->crumbs->add('settings/index', '/', 'System', false); 

     }


    /**
     * Saves a Backoffice edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "index",
                "action" => "index"
            ));
        }

        $this->flash->success("Backoffice was updated successfully");

        //Backoffice Meta
        $metaArray = $this->request->getPost("meta");

        if ($this->request->hasFiles() == true) {

            if(isset($_FILES['academy_image'])){
                foreach ($this->request->getUploadedFiles() as $file){
                    //Move the file into the application
                    $file->moveTo($this->baseBackofficeLocation . $file->getName());
                    $path = pathinfo($this->baseBackofficeLocation . $file->getName());
                    
                    $filename = $this->baseBackofficeLocation . 'school' . '.' . $path['extension'];
                    rename($this->baseBackofficeLocation . $path['basename'], $filename);

                }
                $metaArray['academy_image'] = 'school.' . $path['extension'];
            }
        }

        foreach ($metaArray as $metaKey => $metaValue) {
            if(!empty($metaValue)){
                $SettingMeta = Backoffice::findFirst(array(
                    'key = :key:',
                    'bind' => array(    
                        'key' => $metaKey,
                    )
                ));

                    //Update Meta
                    $SettingMeta->key = $metaKey;
                    $SettingMeta->value = $metaValue;

                    if (!$SettingMeta->save()) {
                        foreach ($SettingMeta->getMessages() as $message) {
                            $this->flash->error($message);
                        }
                    }
                
            }
        }

        return $this->dispatcher->forward(array(
            "controller" => "index",
            "action" => "index"
        ));

    }

    // public function setLanguageAction($language='')
    // {
    //     //Change the language, reload translations if needed
    //     if ($language == 'en' || $language == 'th') {
    //         $this->session->set('language', $language);
    //         $this->loadMainTrans();
    //         $this->loadCustomTrans('index');
    //     }

    //     //Go to the last place
    //     $referer = $this->request->getHTTPReferer();
    //     if (strpos($referer, $this->request->getHttpHost()."/")!==false) {
    //         return $this->response->setHeader("Location", $referer);
    //     } else {
    //         return $this->dispatcher->forward(array('controller' => 'index', 'action' => 'index'));
    //     }
    // }

}