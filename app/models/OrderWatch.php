<?php
namespace Phoenix\Models;

use Phalcon\Mvc\Model\Validator\Email as Email;

class OrderWatch extends \Phalcon\Mvc\Model
{

// select `o`.`customer_id` AS `customer_id`,`o`.`date_added` AS `date_added`,`o`.`total` AS `total`,`o`.`shipping_zone` AS `shipping_zone`,`o`.`payment_zone` AS `payment_zone`,`o`.`order_id` AS `order_id`,`o`.`firstname` AS `firstname`,`o`.`lastname` AS `lastname`,`o`.`email` AS `email`,`o`.`telephone` AS `telephone`,`o`.`fax` AS `fax`,`og`.`name` AS `customer_group` from ((`order` `o` left join `order_status` `os` on((`o`.`order_status_id` = `os`.`order_status_id`))) left join `customer_group_description` `og` on(((`o`.`customer_group_id` = `og`.`customer_group_id`) and (`og`.`language_id` = 1)))) where ((`o`.`date_added` >= '2014-01-01') and (`os`.`language_id` = 1) and (`o`.`order_status_id` = 3)) order by `o`.`customer_id`,`o`.`date_added`
    /**
     *
     * @var integer
     */
    public $customer_id;

    /**
     *
     * @var string
     */
    public $date_added;

    /**
     *
     * @var double
     */
    public $total;

    /**
     *
     * @var string
     */
    public $payment_zone;

    /**
     *
     * @var string
     */
    public $shipping_zone;

    /**
     *
     * @var integer
     */
    public $order_id;

    /**
     *
     * @var string
     */
    public $firstname;

    /**
     *
     * @var string
     */
    public $lastname;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $telephone;

    /**
     *
     * @var string
     */
    public $fax;

    /**
     *
     * @var string
     */
    public $customer_group;


    public function initialize()
    {
        $this->setConnectionService('dbOpencart');
    }

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

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'customer_id' => 'customer_id', 
            'date_added' => 'date_added', 
            'total' => 'total', 
            'payment_zone' => 'payment_zone', 
            'shipping_zone' => 'shipping_zone', 
            'order_id' => 'order_id', 
            'firstname' => 'firstname', 
            'lastname' => 'lastname', 
            'email' => 'email', 
            'telephone' => 'telephone', 
            'fax' => 'fax', 
            'customer_group' => 'customer_group'
        );
    }

}
