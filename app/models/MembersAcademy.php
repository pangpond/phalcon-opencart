<?php
namespace Phoenix\Models;

class MembersAcademy extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $academy_id;

    /**
     *
     * @var integer
     */
    public $member_id;

    public function getSource()
    {
        return "members_academy";
    }
    
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'academy_id' => 'academy_id', 
            'member_id' => 'member_id'
        );
    }

}
