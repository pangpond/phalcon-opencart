<?php
namespace Phoenix\Models;

class UsersMeta extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $user_meta_id;

    /**
     *
     * @var integer
     */
    public $user_id;

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

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('users_meta');
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'user_meta_id' => 'user_meta_id', 
            'user_id' => 'user_id', 
            'meta_key' => 'meta_key', 
            'meta_value' => 'meta_value'
        );
    }

}
