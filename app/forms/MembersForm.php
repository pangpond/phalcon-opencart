<?php
namespace Phoenix\Forms;

use Phoenix\Models\Members;
use Phoenix\Models\MembersMeta;
use Phoenix\Models\Geography;
use Phoenix\Models\Province;
use Phoenix\Models\District;
use Phoenix\Models\Area;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;

use Phalcon\Translate\Adapter\NativeArray;

class MembersForm extends Form
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
        $this->loadCustomTrans('members');

        if ($this->session->has("auth")) {
            $auth = $this->session->get("auth");
        }

        if (isset($options['search']) && $options['search']) 
            $elementSelectClass = '';
        else
            $elementSelectClass = ' chosen-select';


        $this->add(new Text("code",
            array('class' => 'form-control', 'id' => 'code', 'placeholder' => $this->controllerTranslate['Code'], 'maxlength' => 10)
        ));

        $this->add(new Select("type", $this->elements->getMembersType(), 
            array(
                'class' => 'form-control', 'id' => 'type',
                'useEmpty' => true,
                'emptyText' => $this->mainTranslate['Please, choose one...'],
                'data-rule-required' => 'true'
            )
        ));

        $this->add(new Select("title", $this->elements->getPrefixNameData(), 
            array(
                'class' => 'form-control', 'id' => 'title',
                'useEmpty' => true,
                'emptyText' => $this->mainTranslate['Please, choose one...'],
                'data-rule-required' => 'true',
            )
        ));

        $this->add(new Text("firstname",
            array('class' => 'form-control', 'id' => 'firstname', 'data-rule-required' => 'true', 'placeholder' => $this->mainTranslate['Firstname'])
        ));

        $this->add(new Text("lastname",
            array('class' => 'form-control', 'id' => 'lastname', 'data-rule-required' => 'true', 'placeholder' => $this->mainTranslate['Lastname'])
        ));

        $this->add(new Text("position",
            array('class' => 'form-control', 'id' => 'position', 'placeholder' => $this->controllerTranslate['Position Name'])
        ));

        $this->add(new Text("citizenid",
            array('class' => 'mask_citizen form-control', 'id' => 'citizenid', 'data-rule-required' => 'true', 'placeholder' => $this->controllerTranslate['Citizen Id'])
        ));

        $this->add(new Select("bloodgroup", $this->elements->getBloodGroup(), 
            array(
                'class' => 'form-control', 'id' => 'bloodgroup',
                'useEmpty' => true,
                'emptyText' => $this->mainTranslate['Please, choose one...'],
            )
        ));

        $this->add(new Text("birthdate",
            array('class' => 'datepick form-control', 'id' => 'birthdate', 'data-date-formataa' => 'yyyy-mm-dd', 'data-rule-required' => 'true')
        ));

        $this->add(new Select("birthday", $this->elements->getDay(), 
            array(
                'class' => 'form-control', 'id' => 'birthday',
                'useEmpty' => true,
                'emptyText' => $this->mainTranslate['Please, choose one...'],
            )
        ));

        $this->add(new Select("birthmonth", $this->elements->getMonth(), 
            array(
                'class' => 'form-control', 'id' => 'birthmonth',
                'useEmpty' => true,
                'emptyText' => $this->mainTranslate['Please, choose one...'],
            )
        ));

        $this->add(new Select("birthyear", $this->elements->getYear(), 
            array(
                'class' => 'form-control', 'id' => 'birthyear',
                'useEmpty' => true,
                'emptyText' => $this->mainTranslate['Please, choose one...'],
            )
        ));

        $this->add(new Text("nationality",
            array('class' => 'form-control', 'id' => 'nationality', 'placeholder' => $this->controllerTranslate['Nationality'], 'value' => 'ไทย')
        ));

        $this->add(new Text("address",
            array('class' => 'form-control', 'id' => 'address', 'data-rule-required' => 'true', 'placeholder' => $this->mainTranslate['Address'], 'maxlength' => 50)
        ));

        $this->add(new Text("address2",
            array('class' => 'form-control', 'id' => 'address2', 'placeholder' => $this->mainTranslate['Address'] . $this->controllerTranslate['Additional'], 'maxlength' => 50)
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

        $this->add(new Select('province', 
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

        $this->add(new Text("area_code",
            array('class' => 'form-control', 'id' => 'area_code', 'maxlength' => 3, 'placeholder' => $this->mainTranslate['Area'])
        ));

        $this->add(new Text("telephone",
            array('class' => 'form-control', 'id' => 'telephone', 'maxlength' => 50, 'placeholder' => $this->mainTranslate['Telephone'])
        ));

         $this->add(new Text("mobile",
            array('class' => 'mask_mobile form-control', 'id' => 'mobile', 'maxlength' => 12, 'placeholder' => $this->mainTranslate['Mobile'])
         ));

         $this->add(new Text("fax",
            array('class' => 'mask_fax form-control', 'id' => 'fax', 'maxlength' => 50, 'placeholder' => $this->mainTranslate['Fax'])
        ));

        $this->add(new Text("registered",
            array('class' => 'datepick form-control', 'id' => 'registered', 'data-date-formataa' => 'yyyy-mm-dd 00:00:00', 'data-rule-required' => 'true')
        ));

        if(isset($auth['role']) && $auth['role'] != 'A')
            $this->add(new Select('areas', Area::find(array(
                    "columns"   =>  "area_id, name",
                    // "cache"     =>  array("key"  =>  "geo", "lifetime" => 86400)
                )), array(
                    'useEmpty'  =>  true,
                    'emptyText' =>  $this->mainTranslate['Please, choose one...'],
                    'using'     => array('area_id', 'name'),
                    'class' => 'form-control',
                    'data-rule-required' => 'true',
                    // 'disabled' => 'true',
                )
            ));
        else
            $this->add(new Select('areas', Area::find(array(
                    "columns"   =>  "area_id, name",
                    "order" => "area_id"
                    // "cache"     =>  array("key"  =>  "geo", "lifetime" => 86400)
                )), array(
                    'useEmpty'  =>  true,
                    'emptyText' =>  $this->mainTranslate['Please, choose one...'],
                    'using'     => array('area_id', 'name'),
                    'class' => 'form-control',
                    'data-rule-required' => 'true',

                )
            ));

        $this->add(new Select('academies', array( null => $this->mainTranslate['Please, choose one...']),
            array(
                'class' => 'form-control',
                'data-rule-required' => 'true'
            )
        ));

        $this->add(new Text("meta[note]",
            array('class' => 'form-control', 'id' => 'note')
        ));

        $this->add(new Text("meta[mate]",
            array('class' => 'form-control', 'id' => 'mate')
        ));

        $this->add(new Text("meta[retired]",
            array('class' => 'datepicker form-control', 'id' => 'retired', 'data-date-formataa' => 'yyyy-mm-dd')
        ));

        $this->add(new Select("standing", $this->elements->getAcademicStanding(), 
            array(
                'class' => 'form-control', 'id' => 'standing',
                'useEmpty' => true,
                'emptyText' => $this->mainTranslate['Please, choose one...'],
                'data-rule-required' => 'true',
            )
        ));

        $this->add(new Select('province_id', Province::find(array(
                "columns"   =>  "province_id, province_name",
                "order" => "province_name",
                // "cache"     =>  array("key"  =>  "province", "lifetime" => 86400)
            )), array(
                'useEmpty'  =>  true,
                'emptyText' =>  $this->mainTranslate['Please, choose one...'],
                'using'     => array('province_id', 'province_name'),
                'class' => 'form-control',
                'data-rule-required' => 'true'
            )
        ));

        $this->add(new Select("title_en", $this->elements->getPrefixNameEnData(), 
            array(
                'class' => 'form-control', 'id' => 'title_en',
                'useEmpty' => true,
                'emptyText' => $this->mainTranslate['Please, choose one...'],
            )
        ));

        $this->add(new Text("meta[firstname_en]",
            array('class' => 'form-control', 'id' => 'firstname_en', 'data-rule-required' => 'true', 'placeholder' => $this->mainTranslate['Firstname EN'])
        ));

        $this->add(new Text("meta[lastname_en]",
            array('class' => 'form-control', 'id' => 'lastname_en', 'data-rule-required' => 'true', 'placeholder' => $this->mainTranslate['Lastname EN'])
        ));

        $this->add(new Select("meta[education_graduate]", $this->elements->getGraduate(), 
            array(
                'class' => 'form-control', 'id' => 'education_graduate',
                'useEmpty' => true,
                'emptyText' => $this->mainTranslate['Please, choose one...'],
            )
        ));

        $this->add(new Text("meta[education_major]",
            array('class' => 'form-control', 'id' => 'major', 'placeholder' => $this->mainTranslate['Major'])
        ));

        $this->add(new Text("meta[education_academy]",
            array('class' => 'form-control', 'id' => 'academy', 'placeholder' => $this->mainTranslate['Academy'])
        ));


    }
}
