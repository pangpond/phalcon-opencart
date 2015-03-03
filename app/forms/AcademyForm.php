<?php
namespace Phoenix\Forms;

use Phoenix\Models\Academy;
use Phoenix\Models\AcademyMeta;
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

class AcademyForm extends Form
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
        $this->loadCustomTrans('academy');

        if (isset($options['search']) && $options['search']) 
            $elementSelectClass = '';
        else
            $elementSelectClass = ' chosen-select';


        $this->add(new Text("code",
            array('class' => 'form-control mask_academyCode', 'id' => 'code', 'data-rule-required' => 'true', 'placeholder' => $this->controllerTranslate['Code'], 'maxlength' => 10, 'data-rule-digits' => 'true')
        ));

        $this->add(new Text("name",
            array('class' => 'form-control', 'id' => 'name', 'data-rule-required' => 'true', 'placeholder' => $this->controllerTranslate['Name'])
        ));

        $this->add(new Text("address",
            array('class' => 'form-control', 'id' => 'address', 'data-rule-required' => 'true', 'placeholder' => $this->mainTranslate['Address'], 'maxlength' => 50)
        ));

        $this->add(new Text("address2",
            array('class' => 'form-control', 'id' => 'address2', 'placeholder' => $this->mainTranslate['Address'], 'maxlength' => 50)
        ));

        $this->add(new Select('geo', Geography::find(array(
                "columns"   =>  "geo_id, geo_name",
                // "cache"     =>  array("key"  =>  "geo", "lifetime" => 86400)
            )), array(
                'useEmpty'  =>  true,
                'emptyText' =>  $this->mainTranslate['Please, choose one...'],
                'using'     => array('geo_id', 'geo_name'),
                'class' => 'form-control',
                'data-rule-required' => 'true'
            )
        ));

        $this->add(new Select('province', Province::find(array(
                "columns"   =>  "province_id, province_name",
                "order" => "province_name"
                // "cache"     =>  array("key"  =>  "province", "lifetime" => 86400)
            )), array(
                'useEmpty'  =>  true,
                'emptyText' =>  $this->mainTranslate['Please, choose one...'],
                'using'     => array('province_id', 'province_name'),
                'class' => 'form-control',
                'data-rule-required' => 'true'
            )
        ));

        $this->add(new Select('provincex', 
            array(null => $this->mainTranslate['Please, choose one...']),
            array(
                'class' => 'form-control',
                'useEmpty' => true,
                'emptyText' => $this->mainTranslate['Please, choose one...'],
                'data-rule-required' => 'true'
            )
        ));

        $this->add(new Select('district', 
            array(null => $this->mainTranslate['Please, choose one...']),
            array(
                'class' => 'form-control',
                'data-rule-required' => 'true'
            )
        ));

        $this->add(new Select('subdistrict', 
            array(null => $this->mainTranslate['Please, choose one...']),
            array(
                'class' => 'form-control',
                'data-rule-required' => 'true'
            )
        ));

        $this->add(new Text("zipcode",
            array('class' => 'form-control', 'id' => 'zipcode', 'data-rule-required' => 'true', 'maxlength' => 5, 'placeholder' => $this->mainTranslate['Zipcode'])
        ));

        $this->add(new Text("email",
            array('class' => 'form-control', 'id' => 'email', 'data-rule-email' => 'true', 'placeholder' => $this->mainTranslate['Email'])
        ));

        $this->add(new Text("telephone",
            array('class' => 'form-control', 'id' => 'telephone', 'maxlength' => 50, 'placeholder' => $this->mainTranslate['Telephone'])
        ));

        $this->add(new Text("fax",
            array('class' => 'mask_fax form-control', 'id' => 'fax', 'maxlength' => 50, 'placeholder' => $this->mainTranslate['Fax'])
        ));

        $this->add(new Text("smis_code",
            array('class' => 'form-control mask_academySMIS', 'id' => 'smis_code', 'data-rule-digits' => 'true', 'placeholder' => $this->controllerTranslate['SMIS Code'])
        ));

        $this->add(new Text("ministry_code",
            array('class' => 'form-control mask_academyMinistry', 'id' => 'ministry_code', 'data-rule-digits' => 'true', 'placeholder' => $this->controllerTranslate['Ministry Code'])
        ));

        $this->add(new Select('division_id', Divisions::find(array(
                "columns"   =>  "division_id, division_name",
                // "cache"     =>  array("key"  =>  "division", "lifetime" => 86400)
            )), array(
                'useEmpty'  =>  true,
                'emptyText' =>  $this->mainTranslate['Please, choose one...'],
                'using'     => array('division_id', 'division_name'),
                'class' => 'form-control',
                'data-rule-required' => 'true'
            )
        ));

        $this->add(new Text("area_id",
            array('class' => 'form-control', 'id' => 'area_id', 'placeholder' => $this->mainTranslate['Area'])
        ));

        $this->add(new Text("last_updated",
            array('class' => 'datepick form-control', 'id' => 'last_updated', 'data-date-formataa' => 'yyyy-mm-dd 00:00:00', 'data-rule-required' => 'true')
        ));

        $this->add(new Text("meta[student-middle]",
            array('class' => 'form-control', 'id' => 'student-middle', 'data-rule-digits' => 'true', 'placeholder' => $this->controllerTranslate['Middle School'])
        ));

        $this->add(new Text("meta[student-high]",
            array('class' => 'form-control', 'id' => 'student-high', 'data-rule-digits' => 'true', 'placeholder' => $this->controllerTranslate['High School'])
        ));

        $this->add(new Text("meta[student-ep]",
            array('class' => 'form-control', 'id' => 'student-ep', 'data-rule-digits' => 'true', 'placeholder' => $this->controllerTranslate['EP'])
        ));

        $this->add(new Text("meta[student-mep]",
            array('class' => 'form-control', 'id' => 'student-mep', 'data-rule-digits' => 'true', 'placeholder' => $this->controllerTranslate['MEP'])
        ));

        $this->add(new Text("meta[student-gifted]",
            array('class' => 'form-control', 'id' => 'student-gifted', 'data-rule-digits' => 'true', 'placeholder' => $this->controllerTranslate['Gifted'])
        ));

        $this->add(new Text("meta[website]",
            array('class' => 'form-control', 'id' => 'website', 'placeholder' => $this->mainTranslate['Website'])
        ));

        $this->add(new textArea("meta[note]",
            array("size" => 30, 'rows' => '3', 'class' => 'form-control', 'id' => 'note', 'placeholder' => $this->mainTranslate['Note'])
        ));

        $this->add(new Select('areas', Area::find(array(
                "columns"   =>  "area_id, name",
                // "cache"     =>  array("key"  =>  "geo", "lifetime" => 86400)
            )), array(
                'useEmpty'  =>  true,
                'emptyText' =>  $this->mainTranslate['Please, choose one...'],
                'using'     => array('area_id', 'name'),
                'class' => 'form-control',
                'data-rule-required' => 'true'
            )
        ));


    }
}
