<?php

namespace Phoenix\Models;

class AreaDirector extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $area_id;

    /**
     *
     * @var integer
     */
    public $people_id;


    public function getSource()
    {
        return "area_director";
    }
    
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'area_id' => 'area_id', 
            'people_id' => 'people_id'
        );
    }

}
