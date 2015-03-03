<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

namespace Phoenix\Models;

use Phalcon\Mvc\Model,
    Phalcon\Mvc\Model\Relation;

class MembersCompleteInfo extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $member_id;

    /**
     *
     * @var string
     */
    public $username;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $code;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $firstname;

    /**
     *
     * @var string
     */
    public $lastname;

    /**
     *
     * @var string
     */
    public $citizenid;

    /**
     *
     * @var string
     */
    public $bloodgroup;

    /**
     *
     * @var string
     */
    public $birthdate;

    /**
     *
     * @var string
     */
    public $nationality;

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
     * @var integer
     */
    public $geo_id;

    /**
     *
     * @var integer
     */
    public $province_id;

    /**
     *
     * @var integer
     */
    public $district_id;

    /**
     *
     * @var integer
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
    public $position;

    /**
     *
     * @var integer
     */
    public $standing;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $area_code;

    /**
     *
     * @var string
     */
    public $telephone;

    /**
     *
     * @var string
     */
    public $mobile;

    /**
     *
     * @var string
     */
    public $fax;

    /**
     *
     * @var string
     */
    public $registered;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $meta_value;

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
        return "members_complete_info";
    }

    public function initialize()
    {
        $this->hasOne('member_id', 'Phoenix\Models\MembersAcademy', 'member_id', 
            array(
                'foreignKey' => array('action' => Relation::ACTION_CASCADE),
                'alias' => 'membersAcademy'
            )
        );
        $this->hasMany("member_id", "Phoenix\Models\Membersmeta", "member_id", array(
            'alias' => 'membersMeta'
        ));

        $this->hasOne('province_id', 'Phoenix\Models\Province', 'province_id', 
            array('alias' => 'province')
        );

        $this->hasOne('district_id', 'Phoenix\Models\District', 'district_id', 
            array('alias' => 'district')
        );

        $this->hasOne('subdistrict_id', 'Phoenix\Models\Subdistrict', 'subdistrict_id', 
            array('alias' => 'subdistrict')
        );
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'member_id' => 'member_id', 
            'username' => 'username', 
            'password' => 'password', 
            'code' => 'code', 
            'type' => 'type', 
            'title' => 'title', 
            'firstname' => 'firstname', 
            'lastname' => 'lastname', 
            'citizenid' => 'citizenid', 
            'bloodgroup' => 'bloodgroup', 
            'birthdate' => 'birthdate', 
            'nationality' => 'nationality', 
            'address' => 'address', 
            'address2' => 'address2', 
            'geo_id' => 'geo_id', 
            'province_id' => 'province_id', 
            'district_id' => 'district_id', 
            'subdistrict_id' => 'subdistrict_id', 
            'zipcode' => 'zipcode', 
            'position' => 'position', 
            'standing' => 'standing', 
            'email' => 'email', 
            'area_code' => 'area_code', 
            'telephone' => 'telephone', 
            'mobile' => 'mobile', 
            'fax' => 'fax', 
            'registered' => 'registered', 
            'status' => 'status', 
            'meta_value' => 'meta_value'
        );
    }

}
