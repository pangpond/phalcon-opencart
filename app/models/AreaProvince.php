<?php
namespace Phoenix\Models;

class AreaProvince extends \Phalcon\Mvc\Model
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
    public $area_id;

    /**
     *
     * @var integer
     */
    public $province_id;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'area_id' => 'area_id', 
            'province_id' => 'province_id'
        );
    }

}
