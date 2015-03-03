<?php
namespace Phoenix\Models;

use Phalcon\Mvc\Model\Validator\Email as Email;

class Users extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $username;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $role;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $active;

   

    /**
     * Validations and business logic
     */
    public function validation()
    {

        $this->validate(
            new Email(
                array(
                    'field'    => 'email',
                    'required' => true,
                )
            )
        );
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    public function getSource()
    {
        return "users";
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany("user_id", "Phoenix\Models\UsersMeta", "user_id", array(
            'alias' => 'usersMeta'
        ));
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'user_id' => 'user_id', 
            'username' => 'username', 
            'password' => 'password', 
            'role' => 'role', 
            'name' => 'name', 
            'email' => 'email', 
            'created_at' => 'created_at', 
            'active' => 'active'
        );
    }

}
