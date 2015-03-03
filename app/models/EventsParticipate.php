<?php
namespace Phoenix\Models;

class EventsParticipate extends \Phalcon\Mvc\Model
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
    public $event_id;

    /**
     *
     * @var integer
     */
    public $member_id;

    /**
     *
     * @var string
     */
    public $checkin;

    public function initialize()
    {

        $this->hasOne('member_id', 'Phoenix\Models\Members', 'member_id', 
            array('foreignKey' => TRUE, 'alias' => 'profile')
        );

    }


    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'event_id' => 'event_id', 
            'member_id' => 'member_id', 
            'checkin' => 'checkin'
        );
    }

}
