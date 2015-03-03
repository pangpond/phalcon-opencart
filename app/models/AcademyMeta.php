<?php
namespace Phoenix\Models;

class AcademyMeta extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $academy_meta_id;

    /**
     *
     * @var integer
     */
    public $academy_id;

    /**
     *
     * @var string
     */
    public $meta_key;

    /**
     *
     * @var string
     */
    public $meta_value;

    public function getSource()
    {
        return "academy_meta";
    }
    
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'academy_meta_id' => 'academy_meta_id', 
            'academy_id' => 'academy_id', 
            'meta_key' => 'meta_key', 
            'meta_value' => 'meta_value'
        );
    }

}
