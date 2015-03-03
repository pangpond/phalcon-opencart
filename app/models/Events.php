<?php
namespace Phoenix\Models;

class Events extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $event_id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $detail;

    /**
     *
     * @var string
     */
    public $venues;

    /**
     *
     * @var string
     */
    public $start;

    /**
     *
     * @var string
     */
    public $end;

    /**
     *
     * @var string
     */
    public $organizer;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'event_id' => 'event_id', 
            'name' => 'name', 
            'detail' => 'detail', 
            'venues' => 'venues', 
            'start' => 'start', 
            'end' => 'end', 
            'organizer' => 'organizer'
        );
    }

}
