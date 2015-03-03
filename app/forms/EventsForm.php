<?php
namespace Phoenix\Forms;

use Phoenix\Models\Events;
// use Phoenix\Models\EventsMeta;
use Phoenix\Models\Geography;
use Phoenix\Models\Province;
use Phoenix\Models\District;
use Phoenix\Models\Area;
use Phoenix\Models\Divisions;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;

use Phalcon\Translate\Adapter\NativeArray;

class EventsForm extends Form
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
        if ($language === 'th' || $language === 'en' || $language === 'cn') {
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

    public function initialize($entity = null, $options = null)
    {
        $this->loadMainTrans();
        $this->loadCustomTrans('events');

        if (isset($options['search']) && $options['search']) 
            $elementSelectClass = '';
        else
            $elementSelectClass = ' chosen-select';


        $this->add(new Text("name",
            array('class' => 'form-control', 'id' => 'name', 'data-rule-required' => 'true', 'placeholder' => $this->controllerTranslate['Name'])
        ));

        $this->add(new TextArea("detail",
            array('class' => 'form-control', 'id' => 'detail', 'data-rule-required' => 'true', 'placeholder' => $this->controllerTranslate['Detail'], 'maxlength' => 50, 'rows' => 5)
        ));

        $this->add(new Text("venues",
            array('class' => 'form-control', 'id' => 'venues', 'placeholder' => $this->controllerTranslate['Venues'], 'maxlength' => 50, 'data-rule-required' => 'true', 'placeholder' => $this->controllerTranslate['Venues'])
        ));

        $this->add(new Text("organizer",
            array('class' => 'form-control', 'id' => 'organizer', 'placeholder' => $this->controllerTranslate['Organizer'], 'maxlength' => 50, 'data-rule-required' => 'true', 'placeholder' => $this->controllerTranslate['Organizer'])
        ));

        $this->add(new Text("start",
            array('class' => 'datepick form-control', 'id' => 'start', 'data-date-formataa' => 'yyyy-mm-dd 00:00:00', 'data-rule-required' => 'true', 'placeholder' => $this->controllerTranslate['Event Start'])
        ));

        $this->add(new Text("end",
            array('class' => 'datepick form-control', 'id' => 'end', 'data-date-formataa' => 'yyyy-mm-dd 00:00:00', 'data-rule-required' => 'true', 'placeholder' => $this->controllerTranslate['Event End'])
        ));


    }
}
