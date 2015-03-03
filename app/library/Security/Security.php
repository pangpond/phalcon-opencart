<?php

namespace Phoenix\Library\Security;

use Phalcon\Events\Event,
	Phalcon\Mvc\User\Plugin,
	Phalcon\Mvc\Dispatcher,
	Phalcon\Acl as Acl,
	Phalcon\Acl\Role as Role,
	Phalcon\Acl\Resource as Resource;
use Phalcon\Tag;

use Phalcon\Mvc\User\Component;

use Phalcon\Acl\Adapter\Memory as Memory;

// use Phoenix\Forms\MembersForm;
use Phoenix\Models\Members;


/**
 * Security
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class Security extends Plugin
{

	public function __construct($dependencyInjector)
	{
		$this->_dependencyInjector = $dependencyInjector;
	}

	public function getAcl()
	{
		if (!isset($this->persistent->acl)) {

			$acl = new Memory();

			// Store serialized list into plain file
    		// file_put_contents("app/cache/acl.data", serialize($acl));


			$acl->setDefaultAction(Acl::DENY);

			//Register roles
			$roles = array(
				'admin' => new Role('Admin'),
				'users' => new Role('Users'),
				'guests' => new Role('Guests')
			);
			foreach ($roles as $role) {
				$acl->addRole($role);
			}
			
			//Private area resources
			$privateResources = array(
				'settings' => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete'),
				'tools' => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete'),
				'backoffice' => array('search'),
				'products' => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete'),
				'producttype' => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete'),
				'integrate' => array('index'),
				'invoices' => array('index'),
				'members' => array('profile', 'province'),
				'rent' => array('history', ),
				'visited' => array('history'),
				'combpersontype' => array('index', 'new', 'edit', 'save', 'create', 'search', 'view'),
			);
			foreach ($privateResources as $resource => $actions) {
				$acl->addResource(new Resource($resource), $actions);
			}

			//Public area resources
			$publicResources = array(
				
				'integrate' => array('index'),
				'about' => array('index'),
				'session' => array('index', 'register', 'start', 'end'),
				'contact' => array('index', 'send'),
				'error' => array('show404', 'show503'),
				// 'index' => array('index'),
				// 'guest' => array('index'),
				'visited' => array('new'),
			);
			foreach ($publicResources as $resource => $actions) {
				$acl->addResource(new Resource($resource), $actions);
			}

			 //Grant access to public areas to both users and guests
			foreach ($roles as $role){
			    foreach ($publicResources as $resource => $actions) {
			    	$acl->allow($role->getName(), $resource, '*');
			    }
			}

			//Grant access to private area to role Users
			foreach ($privateResources as $resource => $actions) {
				foreach ($actions as $action) {
					$acl->allow('Users', $resource, $action);
				}
			}

			//Grant all acess to role Admin
			$acl->allow('Admin', '*', '*');

			//The acl is stored in session, APC would be useful here too
			$this->persistent->acl = $acl;
		}

		return $this->persistent->acl;
	}

	/**
	 * This action is executed before execute any action in the application
	 */
	// public function beforeDispatch(Event $event, Dispatcher $dispatcher)
	public function afterDispatchLoop(Event $event, Dispatcher $dispatcher)
	{
		global $config;
		$auth = $this->session->get('auth');

		if (!$auth){
			$role = 'Guests';
		} else {
			if($auth['role'] == 'A' || $auth['role'] == 'M')
				$role = 'Admin';
			else
				$role = 'Users';
		}


		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();

		$acl = $this->getAcl();


		$allowed = $acl->isAllowed($role, $controller, $action);

		// var_dump($acl);
		// var_dump($allowed);

		// echo "$role, $controller, $action";
		// // Use acl list as needed
		// if ($acl->isAllowed("Guests", "index", "index")) {
		//     echo "Access granted!";
		// } else {
		//     echo "Access denied :(";
		// }


		if ($allowed != Acl::ALLOW) {

			if($acl->getActiveRole() == 'Guests'){
				// $this->flash->error("You don't have access permit to this module please Login.");
				$module = $dispatcher->getModuleName();
				if($module == 'settings' || $module == 'tools' || $module == 'backoffice'){
					$this->view->setViewsDir($config->application->viewsDir);
					$this->view->setLayoutsDir('common/layouts');
					// $this->view->setTemplateAfter('login');
				}

				//injection here to redirect to old blood
				// $this->response->redirect('../th/login.php');

				if (!$this->request->isPost()) {
		            // Tag::setDefault('username', 'admin');
		            // Tag::setDefault('password', 'phalcon');
		        }

				$this->view->setLayout('login');
				// $this->loadCustomTrans('index');

				// $form = new MembersForm(new Members(), array('new' => true));
    //      		$this->view->setVar("form", $form);
				$dispatcher->forward(
					array(
						'controller' => 'session',
						'action' => 'index'
					)
				);
			}
			else if($acl->getActiveRole() == 'Users' || $acl->getActiveRole() == 'Admin'){

				$this->flash->error("Sorry You don't have access permit to this module : " . $controller . "/" . $action);
				$dispatcher->forward(
					array(
						'controller' => 'index',//change this to dashboard
						'action' => 'index'
					)
				);
			}

			return false;
		}

	}

	public function beforeException(Event $event, Dispatcher $dispatcher, $exception)
    {



        //Handle 404 exceptions
        if ($exception instanceof DispatchException) {
            $dispatcher->forward(array(
                'controller' => 'error',
                'action' => 'show404'
            ));
            return false;
        }

// var_dump($exception);
        if(!empty($exception->xdebug_message)){
			$this->flash->error($exception->xdebug_message);

			//Handle other exceptions
			$dispatcher->forward(array(
			    'controller' => 'error',
			    'action' => 'showError',
			));
		}


		switch ($exception->getCode()) {
            case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
            case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                $dispatcher->forward(
                    array(
                        'controller' => 'error',
                        'action'     => 'show404',
                    )
                );
                return false;
        }

        return false;
    }

  //   public function beforeCheckAccess(Event $event)
  //   {
  //   	$acl = $this->getAcl();

  //       //Attach a listener for type "acl"
		// $eventsManager->attach("acl", function($event, $acl) {
		//     if ($event->getType() == "beforeCheckAccess") {
		//          echo   $acl->getActiveRole(),
		//                 $acl->getActiveResource(),
		//                 $acl->getActiveAccess();
		//     }
		// });

  //       return false;
  //   }



}
