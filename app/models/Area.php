<?php
namespace Phoenix\Models;

use Phalcon\Mvc\Model\Validator\Email as Email;

class Area extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $area_id;

    /**
     *
     * @var string
     */
    public $code;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $address;

    /**
     *
     * @var string
     */
    public $province_id;

    /**
     *
     * @var string
     */
    public $district_id;

    /**
     *
     * @var string
     */
    public $subdistrict_id;

    /**
     *
     * @var string
     */
    public $zipcode;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $telephone;

    /**
     *
     * @var string
     */
    public $fax;

    /**
     *
     * @var string
     */
    public $website;


    public function getSource()
    {
        return "area";
    }
    
    /**
     * Validations and business logic
     */
    public function validation()
    {

        $this->validate(
            new Email(
                array(
                    'field'    => 'email',
                    'required' => true,
                )
            )
        );
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    public function initialize()
    {
        $this->hasOne('province_id', 'Phoenix\Models\Province', 'province_id', 
            array('foreignKey' => TRUE, 'alias' => 'province')
        );

        $this->hasOne('district_id', 'Phoenix\Models\District', 'district_id', 
            array('foreignKey' => TRUE, 'alias' => 'district')
        );

        $this->hasOne('subdistrict_id', 'Phoenix\Models\Subdistrict', 'subdistrict_id', 
            array('foreignKey' => TRUE, 'alias' => 'subdistrict')
        );

        $this->hasMany("area_id", "Phoenix\Models\Academy", "area_id");
    }



    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'area_id' => 'area_id', 
            'code' => 'code', 
            'name' => 'name', 
            'address' => 'address', 
            'province_id' => 'province_id', 
            'district_id' => 'district_id', 
            'subdistrict_id' => 'subdistrict_id', 
            'zipcode' => 'zipcode', 
            'email' => 'email', 
            'telephone' => 'telephone', 
            'fax' => 'fax', 
            'website' => 'website', 

        );
    }

}
