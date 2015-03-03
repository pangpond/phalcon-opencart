<?php
namespace Phoenix\Models;

class MembersCard extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $card_id;

    /**
     *
     * @var integer
     */
    public $member_id;

    /**
     *
     * @var string
     */
    public $citizenid;

    /**
     *
     * @var integer
     */
    public $lot_id;

    /**
     *
     * @var string
     */
    public $receive_person;

    /**
     *
     * @var string
     */
    public $receive_date;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'card_id' => 'card_id', 
            'member_id' => 'member_id', 
            'citizenid' => 'citizenid', 
            'lot_id' => 'lot_id', 
            'receive_person' => 'receive_person', 
            'receive_date' => 'receive_date'
        );
    }

}
