<?php
namespace Phoenix\Models;

class Subdistrict extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $subdistrict_id;

    /**
     *
     * @var string
     */
    public $subdistrict_code;

    /**
     *
     * @var string
     */
    public $subdistrict_name;

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

    public function getSource()
    {
        return "subdistrict";
    }
    
    /**
     * Method to set the value of field subdistrict_id
     *
     * @param integer $subdistrict_id
     * @return $this
     */
    public function setSubdistrictId($subdistrict_id)
    {
        $this->subdistrict_id = $subdistrict_id;

        return $this;
    }

    /**
     * Method to set the value of field subdistrict_code
     *
     * @param string $subdistrict_code
     * @return $this
     */
    public function setSubdistrictCode($subdistrict_code)
    {
        $this->subdistrict_code = $subdistrict_code;

        return $this;
    }

    /**
     * Method to set the value of field subdistrict_name
     *
     * @param string $subdistrict_name
     * @return $this
     */
    public function setSubdistrictName($subdistrict_name)
    {
        $this->subdistrict_name = $subdistrict_name;

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
     * Returns the value of field subdistrict_id
     *
     * @return integer
     */
    public function getSubdistrictId()
    {
        return $this->subdistrict_id;
    }

    /**
     * Returns the value of field subdistrict_code
     *
     * @return string
     */
    public function getSubdistrictCode()
    {
        return $this->subdistrict_code;
    }

    /**
     * Returns the value of field subdistrict_name
     *
     * @return string
     */
    public function getSubdistrictName()
    {
        return $this->subdistrict_name;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany("subdistrict_id", "Phoenix\Models\Area", "subdistrict_id");
        $this->hasMany("subdistrict_id", "Phoenix\Models\Academy", "subdistrict_id");
        $this->hasMany("subdistrict_id", "Phoenix\Models\Members", "subdistrict_id");
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'subdistrict_id' => 'subdistrict_id', 
            'subdistrict_code' => 'subdistrict_code', 
            'subdistrict_name' => 'subdistrict_name', 
            'geo_id' => 'geo_id', 
            'province_id' => 'province_id', 
            'district_id' => 'district_id'
        );
    }

}
