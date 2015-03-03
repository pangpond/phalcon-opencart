<?php
namespace Phoenix\Models;

class Settings extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $setting_id;

    /**
     *
     * @var string
     */
    public $settingkey;

    /**
     *
     * @var string
     */
    public $setting_value;

    /**
     * Method to set the setting_value of field setting_id
     *
     * @param integer $setting_id
     * @return $this
     */
    public function setSettingId($setting_id)
    {
        $this->setting_id = $setting_id;

        return $this;
    }

    /**
     * Method to set the setting_value of field settingkey
     *
     * @param string $settingkey
     * @return $this
     */
    public function setKey($settingkey)
    {
        $this->settingkey = $settingkey;

        return $this;
    }

    /**
     * Method to set the setting_value of field setting_value
     *
     * @param string $setting_value
     * @return $this
     */
    public function setValue($setting_value)
    {
        $this->setting_value = $setting_value;

        return $this;
    }

    /**
     * Returns the setting_value of field setting_id
     *
     * @return integer
     */
    public function getSettingId()
    {
        return $this->setting_id;
    }

    /**
     * Returns the setting_value of field settingkey
     *
     * @return string
     */
    public function getKey()
    {
        return $this->settingkey;
    }

    /**
     * Returns the setting_value of field setting_value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->setting_value;
    }

    public function getSource()
    {
        return "settings";
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'setting_id' => 'setting_id', 
            'settingkey' => 'settingkey', 
            'setting_value' => 'setting_value'
        );
    }

}
