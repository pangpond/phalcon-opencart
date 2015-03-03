<?php
namespace Modules\Settings\Controllers;

use Phalcon\Tag,
    // Modules\Settings\Controllers,
    Phalcon\Session as Session;

use Phoenix\Models\Settings;
use Phoenix\Models\Members;

use Phoenix\Controllers\ControllerBase;

class IndexController extends ControllerBase
{
    protected $defaultMemberImage = 'school.jpg';
    protected $baseLocation = 'uploads/academy/';

    public function initialize()
    {
        // $view->setTemplateAfter('main');
        Tag::setTitle('Setting Index');
        $this->loadCustomTrans('settings/index');
        parent::initialize();

        $this->crumbs->add('settings', '', 'Settings'); 
        
    }

    public function indexAction()
    {

        $language = $this->session->get('language');
        //Get session info
        $auth = $this->session->get('auth');

        //Query the active user
        $user = Members::findFirst($auth['id']);
        if ($user == false || empty($auth['id'])) {
            //$this->redirectNew('index');
            $this->response->redirect('settings/academicyears');
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
                     $this->view->setVar("academyImage", $this->baseLocation . $setting->value);


                 if($setting->key == 'rules')
                    $settingRules = json_decode($setting->value);

                if(isset($settingRules) && is_object($settingRules)){
                    $this->tag->setDefault("meta[rule_1]", $settingRules->rule_1);
                    $this->tag->setDefault("meta[rule_2]", $settingRules->rule_2);
                    $this->tag->setDefault("meta[rule_3]", $settingRules->rule_3);
                    // $this->tag->setDefault("meta[rule_4]", $settingRules->rule_4);
                    // $this->tag->setDefault("meta[rule_5]", $settingRules->rule_5);
                    // $this->tag->setDefault("meta[rule_6]", $settingRules->rule_6);
                    // $this->tag->setDefault("meta[rule_7]", $settingRules->rule_7);
                    // $this->tag->setDefault("meta[rule_8]", $settingRules->rule_8);
                    // $this->tag->setDefault("meta[rule_9]", $settingRules->rule_9);
                    // $this->tag->setDefault("meta[rule_10]", $settingRules->rule_10);
                    // $this->tag->setDefault("meta[rule_11]", $settingRules->rule_11);
                    // $this->tag->setDefault("meta[rule_12]", $settingRules->rule_12);
                }
            }
        $this->crumbs->add('settings/index', '/', 'System', false); 

     }


    /**
     * Saves a Settings edited
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

        $this->flash->success("Settings was updated successfully");

        //Settings Meta
        $metaArray = $this->request->getPost("meta");

        if ($this->request->hasFiles() == true) {

            if(isset($_FILES['academy_image'])){
                foreach ($this->request->getUploadedFiles() as $file){
                    //Move the file into the application
                    $file->moveTo($this->baseLocation . $file->getName());
                    $path = pathinfo($this->baseLocation . $file->getName());
                    
                    $filename = $this->baseLocation . 'school' . '.' . $path['extension'];
                    rename($this->baseLocation . $path['basename'], $filename);

                }
                $metaArray['academy_image'] = 'school.' . $path['extension'];
            }
        }
        if(!empty($metaArray['rule_1']) || !empty($metaArray['rule_1']) || !empty($metaArray['rule_3'])){

            $metaRulesArray = array(
                                'rule_1' => $metaArray['rule_1'], 
                                'rule_2' => $metaArray['rule_2'], 
                                'rule_3' => $metaArray['rule_3']
                            );

            $metaArray['rules'] = json_encode($metaRulesArray);
            $metaArray['rule_1'] = $metaArray['rule_2'] = $metaArray['rule_3'] = '';
        }



        foreach ($metaArray as $metaKey => $metaValue) {
            if(!empty($metaValue)){
                $SettingMeta = Settings::findFirst(array(
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