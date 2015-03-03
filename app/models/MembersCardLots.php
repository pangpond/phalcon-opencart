<?php
namespace Phoenix\Models;

class MembersCardLots extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $lot_id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $date_start;

    /**
     *
     * @var string
     */
    public $date_finish;

    /**
     *
     * @var string
     */
    public $year;

    /**
     *
     * @var string
     */
    public $month;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'lot_id' => 'lot_id', 
            'name' => 'name', 
            'date_start' => 'date_start', 
            'date_finish' => 'date_finish', 
            'year' => 'year', 
            'month' => 'month'
        );
    }

}
