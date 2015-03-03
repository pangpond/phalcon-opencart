<?php
namespace Phoenix\Models;

use Phalcon\Mvc\Model\Validator\Email as Email;

class Academy extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $academy_id;

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
    public $address2;

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
    public $smis_code;

    /**
     *
     * @var string
     */
    public $ministry_code;

    /**
     *
     * @var integer
     */
    public $division_id;

    /**
     *
     * @var integer
     */
    public $area_id;

    /**
     *
     * @var date
     */
    public $last_updated;


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

    public function getSource()
    {
        return "academy";
    }

    public function initialize()
    {
        $this->hasOne('province_id', 'Phoenix\Models\Province', 'province_id', 
            array('alias' => 'province')
        );

        $this->hasOne('district_id', 'Phoenix\Models\District', 'district_id', 
            array('alias' => 'district')
        );

        $this->hasOne('subdistrict_id', 'Phoenix\Models\Subdistrict', 'subdistrict_id', 
            array('alias' => 'subdistrict')
        );

        $this->hasOne('area_id', 'Phoenix\Models\Area', 'area_id', 
            array('alias' => 'area')
        );

        $this->hasOne('district_id', 'Phoenix\Models\Divisions', 'division_id', 
            array('alias' => 'division')
        );

        $this->hasMany("academy_id", "Phoenix\Models\AcademyMeta", "academy_id", 
            array(
            'alias' => 'academyMeta'
        ));
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'academy_id' => 'academy_id', 
            'code' => 'code', 
            'name' => 'name', 
            'address' => 'address', 
            'address2' => 'address2', 
            'province_id' => 'province_id', 
            'district_id' => 'district_id', 
            'subdistrict_id' => 'subdistrict_id', 
            'zipcode' => 'zipcode', 
            'email' => 'email', 
            'telephone' => 'telephone', 
            'fax' => 'fax', 
            'smis_code' => 'smis_code', 
            'ministry_code' => 'ministry_code', 
            'division_id' => 'division_id', 
            'area_id' => 'area_id', 
            'last_updated' => 'last_updated',
        );
    }

}
