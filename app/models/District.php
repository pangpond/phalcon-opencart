<?php
namespace Phoenix\Models;

class District extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $district_id;

    /**
     *
     * @var string
     */
    public $district_code;

    /**
     *
     * @var string
     */
    public $district_name;

    /**
     *
     * @var string
     */
    public $district_name_en;

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
     * Method to set the value of field district_id
     *
     * @param integer $district_id
     * @return $this
     */
    public function setDistrictId($district_id)
    {
        $this->district_id = $district_id;

        return $this;
    }

    /**
     * Method to set the value of field district_code
     *
     * @param string $district_code
     * @return $this
     */
    public function setDistrictCode($district_code)
    {
        $this->district_code = $district_code;

        return $this;
    }

    /**
     * Method to set the value of field district_name
     *
     * @param string $district_name
     * @return $this
     */
    public function setDistrictName($district_name)
    {
        $this->district_name = $district_name;

        return $this;
    }

    /**
     * Method to set the value of field district_name_en
     *
     * @param string $district_name_en
     * @return $this
     */
    public function setDistrictNameEn($district_name_en)
    {
        $this->district_name_en = $district_name_en;

        return $this;
    }

    /**
     * Method to set the value of field geo_id
     *
     * @param integer $geo_id
     * @return $this
     */
    public function setGeoId($geo_id)
    {
        $this->geo_id = $geo_id;

        return $this;
    }

    /**
     * Method to set the value of field province_id
     *
     * @param integer $province_id
     * @return $this
     */
    public function setProvinceId($province_id)
    {
        $this->province_id = $province_id;

        return $this;
    }

    /**
     * Returns the value of field district_id
     *
     * @return integer
     */
    public function getDistrictId()
    {
        return $this->district_id;
    }

    /**
     * Returns the value of field district_code
     *
     * @return string
     */
    public function getDistrictCode()
    {
        return $this->district_code;
    }

    /**
     * Returns the value of field district_name
     *
     * @return string
     */
    public function getDistrictName()
    {
        return $this->district_name;
    }

    /**
     * Returns the value of field district_name_en
     *
     * @return string
     */
    public function getDistrictNameEn()
    {
        return $this->district_name_en;
    }

    /**
     * Returns the value of field geo_id
     *
     * @return integer
     */
    public function getGeoId()
    {
        return $this->geo_id;
    }

    /**
     * Returns the value of field province_id
     *
     * @return integer
     */
    public function getProvinceId()
    {
        return $this->province_id;
    }

    public function getSource()
    {
        return "district";
    }
    
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany("district_id", "Phoenix\Models\Area", "district_id");
        $this->hasMany("district_id", "Phoenix\Models\Academy", "district_id");
        $this->hasMany("district_id", "Phoenix\Models\Members", "district_id");
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'district_id' => 'district_id', 
            'district_code' => 'district_code', 
            'district_name' => 'district_name', 
            'district_name_en' => 'district_name_en', 
            'geo_id' => 'geo_id', 
            'province_id' => 'province_id'
        );
    }

}
