<?php
namespace Phoenix\Models;

class Geography extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $geo_id;

    /**
     *
     * @var string
     */
    public $geo_name;

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
     * Method to set the value of field geo_name
     *
     * @param string $geo_name
     * @return $this
     */
    public function setGeoName($geo_name)
    {
        $this->geo_name = $geo_name;

        return $this;
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
     * Returns the value of field geo_name
     *
     * @return string
     */
    public function getGeoName()
    {
        return $this->geo_name;
    }

    public function getSource()
    {
        return "geography";
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('geography');
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'geo_id' => 'geo_id', 
            'geo_name' => 'geo_name'
        );
    }

}
