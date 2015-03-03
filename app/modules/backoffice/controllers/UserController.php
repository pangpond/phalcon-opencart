<?php

namespace Modules\Settings\Controllers;

use Modules\Settings\Models\Users;

class UserController extends \Phalcon\Mvc\Controller
{

  public function indexAction()
  {
    $this->view->setVar("name", "Mike");
    $this->view->setVar("t", $this->_getTranslation());
  }

}