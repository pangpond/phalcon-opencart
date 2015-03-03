<?php
namespace Modules\Settings\Models;

use Phalcon\Mvc\Model\Validator\Email as EmailValidator;
use Phalcon\Mvc\Model\Validator\Uniqueness as UniquenessValidator;


class Users extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('ComBPersonType');
    }
    
    /**
     * Validations and business logic
     */
    public function validation()
    {

        $this->validate(
            new EmailValidator(
                array(
                    'field'    => 'email',
                    'required' => true,
                )
            )
        );

        $this->validate(new EmailValidator(array(
            'field' => 'email'
        )));
        $this->validate(new UniquenessValidator(array(
            'field' => 'email',
            'message' => 'Sorry, The email was registered by another user'
        )));
        $this->validate(new UniquenessValidator(array(
            'field' => 'username',
            'message' => 'Sorry, That username is already taken'
        )));
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    /**
     * Independent Column Mapping.
     */
    // public function columnMap()
    // {
    //     return array(
    //         'id' => 'id', 
    //         'name' => 'name', 
    //         'email' => 'email'
    //     );
    // }

}
