<?php
namespace Modules\Settings\Models;

class Combpersontype extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $PersonTypeID;

    /**
     *
     * @var string
     */
    public $PersonTypeName;

    /**
     *
     * @var string
     */
    public $Version;

    /**
     *
     * @var integer
     */
    public $UserID;

    /**
     *
     * @var integer
     */
    public $ActiveStatus;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('ComBPersonType');
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'PersonTypeID' => 'PersonTypeID', 
            'PersonTypeName' => 'PersonTypeName', 
            'Version' => 'Version', 
            'UserID' => 'UserID', 
            'ActiveStatus' => 'ActiveStatus'
        );
    }

}
