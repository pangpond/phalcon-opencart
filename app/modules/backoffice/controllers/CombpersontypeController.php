<?php

namespace Modules\Settings\Controllers;

use Phalcon\Mvc\Model\Criteria,
    Phalcon\Paginator\Adapter\Model as Paginator,
    Phalcon\Translate\Adapter\NativeArray,
    Phalcon\Tag,
    Phalcon\Mvc\Url,
    Phalcon\Paginator\Adapter\Model;
    

use Modules\Settings\Models\Combpersontype;


class CombpersontypeController extends ControllerBase
{

    public function initialize()
    {
        // $this->view->setTemplateAfter('main');
        Tag::setTitle('Person Type');
        $this->loadCustomTrans('settings/persontype');
        parent::initialize();

        $this->crumbs->add('persontype', '/', 'persontype', false); 
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $numberPage = 1;

        $numberPage = $this->request->getQuery("page", "int");
            if ($numberPage <= 0) {
                $numberPage = 1;
            }

        $parameters = array();

        $products = Combpersontype::find($parameters);
        if (count($products) == 0) {
            $this->flash->notice("The search did not find any products");
            return $this->forward("settings/persontype");
        }

        $paginator = new Model(array(
            "data" => $products,
            "limit" => 100,
            "page" => $numberPage
        ));
        $page = $paginator->getPaginate();

        $this->view->setVar("page", $page);

    }

    /**
     * Page action
     */
    public function pageAction()
    {
        //Get session info
        $auth = $this->session->get('auth');

        $numberPage = 1;

        $numberPage = $this->request->getQuery("page", "int");
            if ($numberPage <= 0) {
                $numberPage = 1;
            }

        $parameters = array();

        $products = Combpersontype::find($parameters);
        if (count($products) == 0) {
            $this->flash->notice("The search did not find any products");
            return $this->forward("settings/persontype/page");
        }

        $paginator = new Paginator(array(
            "data" => $products,
            "limit" => 10,
            "page" => $numberPage
        ));
        $page = $paginator->getPaginate();

        $this->view->setVar("page", $page);

    }

    /**
     * Searches for ComBPersonType
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Combpersontype", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "PersonTypeID";

        $ComBPersonType = Combpersontype::find($parameters);
        if (count($ComBPersonType) == 0) {
            $this->flash->notice("The search did not find any ComBPersonType");

            return $this->dispatcher->forward(array(
                // "controller" => "ComBPersonType",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $ComBPersonType,
            "limit"=> 2,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displayes the creation form
     */
    public function newAction()
    {
        if ($this->session->has("auth")) {
            $auth = $this->session->get("auth");
        }

        $this->tag->setDefault("Version", date('YmdHis'));
        
        if(isset($auth) && $auth != '')
            $this->tag->setDefault("UserID", $auth['id']);

        $this->crumbs->add('persontype', '/phoenix/settings/persontype', 'persontype');
        $this->crumbs->add('new', '/', 'new', false); 
    }

    /**
     * Edits a ComBPersonType
     *
     * @param string $PersonTypeID
     */
    public function editAction($PersonTypeID)
    {

        if (!$this->request->isPost()) {

            $ComBPersonType = Combpersontype::findFirstByPersonTypeID($PersonTypeID);
            if (!$ComBPersonType) {
                $this->flash->error("Person Type was not found");

                return $this->dispatcher->forward(array(
                    // "controller" => "ComBPersonType",
                    "action" => "index"
                ));
            }

            $this->view->PersonTypeID = $ComBPersonType->PersonTypeID;

            $this->tag->setDefault("PersonTypeID", $ComBPersonType->PersonTypeID);
            $this->tag->setDefault("PersonTypeName", $ComBPersonType->PersonTypeName);
            $this->tag->setDefault("Version", $ComBPersonType->Version);
            $this->tag->setDefault("UserID", $ComBPersonType->UserID);
            $this->tag->setDefault("ActiveStatus", $ComBPersonType->ActiveStatus);
            
            $this->crumbs->add('persontype', '/phoenix/settings/persontype', 'persontype');
            
            if(empty($this->crumbs->crumbs['edit']['label']))
                $this->crumbs->add('edit', '/', 'edit', false); 

        }
    }

    public function viewAction($PersonTypeID)
    {

        $this->crumbs->add('edit', '/', 'view', false); 

        return $this->dispatcher->forward(array(
            "action" => "edit"
        ));
    }

    /**
     * Creates a new ComBPersonType
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                // "controller" => "ComBPersonType",
                "action" => "index"
            ));
        }

        $ComBPersonType = new Combpersontype();

        $ComBPersonType->PersonTypeID = $this->request->getPost("PersonTypeID");
        $ComBPersonType->PersonTypeName = $this->request->getPost("PersonTypeName");
        $ComBPersonType->Version = $this->request->getPost("Version");
        $ComBPersonType->UserID = $this->request->getPost("UserID");
        $ComBPersonType->ActiveStatus = $this->request->getPost("ActiveStatus");

        
        

        if (!$ComBPersonType->save()) {
            foreach ($ComBPersonType->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "new"
            ));
        }
        $buttonDismiss = '<button type="button" class="close" data-dismiss="alert">×</button>';
        $this->flash->success("Person Type was created successfully " . $buttonDismiss);

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));

    }

    /**
     * Saves a ComBPersonType edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $PersonTypeID = $this->request->getPost("PersonTypeID");

        $ComBPersonType = Combpersontype::findFirstByPersonTypeID($PersonTypeID);
        if (!$ComBPersonType) {
            $this->flash->error("ComBPersonType does not exist " . $PersonTypeID);

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $ComBPersonType->PersonTypeID = $this->request->getPost("PersonTypeID");
        $ComBPersonType->PersonTypeName = $this->request->getPost("PersonTypeName");
        $ComBPersonType->Version = $this->request->getPost("Version");
        $ComBPersonType->UserID = $this->request->getPost("UserID");
        $ComBPersonType->ActiveStatus = $this->request->getPost("ActiveStatus");
        

        if (!$ComBPersonType->save()) {

            foreach ($ComBPersonType->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "edit",
                "params" => array($ComBPersonType->PersonTypeID)
            ));
        }

        $this->flash->success("ComBPersonType was updated successfully");

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));

    }

    /**
     * Deletes a ComBPersonType
     *
     * @param string $PersonTypeID
     */
    public function deleteAction($PersonTypeID)
    {
        
        // Getting a request instance
        $request = new \Phalcon\Http\Request();

        // Check whether the request was made with method POST
        if ($request->isPost() == true) {

            // Check whether the request was made with Ajax
            if ($request->isAjax() == true) {
                echo "Request was made using POST and AJAX";
            }
        }

        $ComBPersonType = Combpersontype::findFirstByPersonTypeID($PersonTypeID);
        if (!$ComBPersonType) {
            $this->flash->error("ComBPersonType was not found");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        if (!$ComBPersonType->delete()) {

            foreach ($ComBPersonType->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "search"
            ));
        }

        $buttonDismiss = '<button type="button" class="close" data-dismiss="alert">×</button>';
        $this->flash->success("ComBPersonType was deleted successfully" . $buttonDismiss);

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));
    }

}
