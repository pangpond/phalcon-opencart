<?php
namespace Phoenix\Models;

class MembersLog extends \Phalcon\Mvc\Model
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
    public $province_id;

    /**
     *
     * @var string
     */
    public $province;

    /**
     *
     * @var string
     */
    public $username;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var integer
     */
    public $members;

    /**
     *
     * @var string
     */
    public $completed;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'province_id' => 'province_id', 
            'province' => 'province', 
            'username' => 'username', 
            'name' => 'name', 
            'members' => 'members', 
            'completed' => 'completed'
        );
    }

}
