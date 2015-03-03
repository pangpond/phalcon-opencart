<?php
namespace Phoenix\Forms;


use Phoenix\Models\AcademicYears;


use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;

use Phalcon\Translate\Adapter\NativeArray;

class AcademicyearsForm extends Form
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

    public function initialize($entity = null, $options = null)
    {
        $this->loadMainTrans();

        if (isset($options['search']) && $options['search']) {
            $elementSelectClass = '';

            $this->add(new Text("name",
                array('class' => 'form-control', 'id' => 'name')
            ));

            $this->add(new Select('term', array(
            1 => '1',
            2 => '2',
            3 => '3'
            )));

            $this->add(new Text("start",
                array('class' => 'form-control', 'id' => 'start')
            ));

            $this->add(new Text("end",
                array('class' => 'form-control', 'id' => 'end')
            ));

        }else{
            $elementSelectClass = ' chosen-select';


            $this->add(new Text("name",
                array('class' => 'form-control', 'id' => 'name')
            ));

            $this->add(new Select('term', array(
            1 => '1',
            2 => '2',
            3 => '3'
            )));

            $this->add(new Text("start",
                array('class' => 'datepick form-control', 'id' => 'start', 'data-date-formataa' => 'yyyy-mm-dd')
            ));

            $this->add(new Text("end",
                array('class' => 'datepick form-control', 'id' => 'end', 'data-date-formataa' => 'yyyy-mm-dd')
            ));
        }

    }
}
