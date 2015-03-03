<?php
namespace Phoenix\Models;

class Divisions extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $division_id;

    /**
     *
     * @var string
     */
    public $division_code;

    /**
     *
     * @var string
     */
    public $division_name;

    /**
     *
     * @var string
     */
    public $division_under;

    /**
     * Method to set the value of field division_id
     *
     * @param integer $division_id
     * @return $this
     */
    public function setDivisionId($division_id)
    {
        $this->division_id = $division_id;

        return $this;
    }

    /**
     * Method to set the value of field division_code
     *
     * @param string $division_code
     * @return $this
     */
    public function setDivisionCode($division_code)
    {
        $this->division_code = $division_code;

        return $this;
    }

    /**
     * Method to set the value of field division_name
     *
     * @param string $division_name
     * @return $this
     */
    public function setDivisionName($division_name)
    {
        $this->division_name = $division_name;

        return $this;
    }

    /**
     * Method to set the value of field division_under
     *
     * @param string $division_under
     * @return $this
     */
    public function setDivisionUnder($division_under)
    {
        $this->division_under = $division_under;

        return $this;
    }

    /**
     * Returns the value of field division_id
     *
     * @return integer
     */
    public function getDivisionId()
    {
        return $this->division_id;
    }

    /**
     * Returns the value of field division_code
     *
     * @return string
     */
    public function getDivisionCode()
    {
        return $this->division_code;
    }

    /**
     * Returns the value of field division_name
     *
     * @return string
     */
    public function getDivisionName()
    {
        return $this->division_name;
    }

    /**
     * Returns the value of field division_under
     *
     * @return string
     */
    public function getDivisionUnder()
    {
        return $this->division_under;
    }


    public function getSource()
    {
        return "divisions";
    }
    
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany("division_id", "Phoenix\Models\Academy", "division_id");
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'division_id' => 'division_id', 
            'division_code' => 'division_code', 
            'division_name' => 'division_name', 
            'division_under' => 'division_under'
        );
    }

}
