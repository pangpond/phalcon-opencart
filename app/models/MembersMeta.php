<?php
namespace Phoenix\Models;

class MembersMeta extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $member_meta_id;

    /**
     *
     * @var integer
     */
    public $member_id;

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
        return "members_meta";
    }
    
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        // $this->setSource('Members_Meta');
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'member_meta_id' => 'member_meta_id', 
            'member_id' => 'member_id', 
            'meta_key' => 'meta_key', 
            'meta_value' => 'meta_value'
        );
    }

}
