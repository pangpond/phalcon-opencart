<?php
namespace Phoenix\Models;

class MembersCode extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $code;

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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('members_code');
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'code' => 'code', 
            'title' => 'title', 
            'firstname' => 'firstname', 
            'lastname' => 'lastname'
        );
    }

}
