<?php
namespace Phoenix\Models;

class Province extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $province_id;

    /**
     *
     * @var string
     */
    public $province_code;

    /**
     *
     * @var string
     */
    public $province_name;

    /**
     *
     * @var string
     */
    public $province_name_en;

    /**
     *
     * @var integer
     */
    public $geo_id;

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
     * Method to set the value of field province_code
     *
     * @param string $province_code
     * @return $this
     */
    public function setProvinceCode($province_code)
    {
        $this->province_code = $province_code;

        return $this;
    }

    /**
     * Method to set the value of field province_name
     *
     * @param string $province_name
     * @return $this
     */
    public function setProvinceName($province_name)
    {
        $this->province_name = $province_name;

        return $this;
    }

    /**
     * Method to set the value of field province_name_en
     *
     * @param string $province_name_en
     * @return $this
     */
    public function setProvinceNameEn($province_name_en)
    {
        $this->province_name_en = $province_name_en;

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
     * Returns the value of field province_id
     *
     * @return integer
     */
    public function getProvinceId()
    {
        return $this->province_id;
    }

    /**
     * Returns the value of field province_code
     *
     * @return string
     */
    public function getProvinceCode()
    {
        return $this->province_code;
    }

    /**
     * Returns the value of field province_name
     *
     * @return string
     */
    public function getProvinceName()
    {
        return $this->province_name;
    }

    /**
     * Returns the value of field province_name_en
     *
     * @return string
     */
    public function getProvinceNameEn()
    {
        return $this->province_name_en;
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

    public function getSource()
    {
        return "province";
    }
    
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany("province_id", "Phoenix\Models\Area", "province_id");
        $this->hasMany("province_id", "Phoenix\Models\Academy", "province_id");
        $this->hasMany("province_id", "Phoenix\Models\Members", "province_id");
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'province_id' => 'province_id', 
            'province_code' => 'province_code', 
            'province_name' => 'province_name', 
            'province_name_en' => 'province_name_en', 
            'geo_id' => 'geo_id'
        );
    }

}
