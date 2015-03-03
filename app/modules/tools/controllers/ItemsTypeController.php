<?php
namespace Modules\Tools\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phoenix\Models\ItemsType;

use Phalcon\Tag,
    Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Select,
    Phalcon\Forms\Element\TextArea;

use Phoenix\Forms\ItemsTypeForm;

use Phoenix\Controllers\ControllerBase;

class ItemsTypeController extends ControllerBase
{
    protected $defaultIndexLimit = 30;
    protected $defaultPageLimit = 10;
    protected $defaultNullData = 'N/A';

    public function initialize()
    {
        global $config;
        Tag::setTitle('Items Type');
        $this->loadCustomTrans('settings/items_type');
        parent::initialize();

        $this->crumbs->add('settings', $config->application->baseUri.'settings', 'Settings'); 
        
        $this->crumbs->add('items_type', $config->application->baseUri.'settings/items_type', 'Items Type');
    }

    /**
     * Index action
     */
    public function indexAction()
    {

        $this->persistent->parameters = null;

        $numberPage = $this->request->getQuery("page", "int");
        if(empty($numberPage))
            $numberPage = 1;

        $itemsType = ItemsType::find(array(
            "order" => "type_id ASC",
            "limit" => $this->defaultIndexLimit,
        ));

        if (count($itemsType) == 0) {
            $this->flash->notice("The search did not find any items");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $itemsType,
            "limit"=> $this->defaultPageLimit,
            "page" => $numberPage
        ));

        $form = new ItemsTypeForm(new ItemsType(), array('search' => true));
        $this->view->setVar("form", $form);

        $this->view->page = $paginator->getPaginate();


        $this->crumbs->add('items_type', '/', 'Items Type', false); 
        $this->view->setVar('searchType', 'index');
        $this->view->setVar('defaultNullData', $this->defaultNullData);
    }

    public function basicSearchAction()
    {
        global $config;

        $numberPage = 1;
        if ($this->request->isPost()) {

          $parameters['conditions'] = 'code LIKE :code:
                                        OR name LIKE :name:';

          $parameters['bind'] =  array (
                                    'code' => '%' . $_POST['keyword'] . '%',
                                    'name' => '%' . $_POST['keyword'] . '%',
                                );
          $this->persistent->parameters = $parameters;
          $this->persistent->keyword = $_POST['keyword'];
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }

        $parameters["order"] = "type_id";

        $itemsType = ItemsType::find($parameters);

        if (count($itemsType) == 0) {
            $this->flash->notice("The search did not find any type");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $itemsType,
            "limit"=> $this->defaultPageLimit,
            "page" => $numberPage
        ));
        
        if(!empty($this->persistent->keyword))
            $this->view->setVar('keyword', $this->persistent->keyword);

        $this->view->page = $paginator->getPaginate();
        $this->view->setVar('searchType', 'basicsearch');
        $this->view->pick(array("items_type/search"));

        $this->crumbs->add('Search', '/', 'Search Result(s)', false); 
    }

    /**
     * Searches for items
     */
    public function searchAction()
    {
        global $config;
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Phoenix\Models\ItemsType", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "type_id";

        $itemsType = ItemsType::find($parameters);
        if (count($itemsType) == 0) {
            $this->flash->notice("The search did not find any type");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $itemsType,
            "limit"=> $this->defaultPageLimit,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
        $this->view->setVar('searchType', 'search');

        $this->crumbs->add('Search', '/', 'Search Result(s)', false); 

    }

    /**
     * Displayes the creation form
     */
    public function newAction()
    {
        global $config;

        if(empty($this->crumbs->crumbs['new']['label']))
            $this->crumbs->add('New', '/', 'New', false); 

        $form = new ItemsTypeForm(new ItemsType(), array('new' => true));
        $this->view->setVar("form", $form);
    }

    /**
     * Edits a items_type
     *
     * @param string $type_id
     */
    public function editAction($type_id)
    {

        global $config;
        if (!$this->request->isPost()) {

            $type = ItemsType::findFirstBytype_id($type_id);
            if (!$type) {
                $this->flash->error("item was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "items",
                    "action" => "index"
                ));
            }

            $this->view->type_id = $type->type_id;

            $this->tag->setDefault("type_id", $type->type_id);
            $this->tag->setDefault("code", $type->code);
            $this->tag->setDefault("name", $type->name);

            if(empty($this->crumbs->crumbs['Edit']['label']))
                $this->crumbs->add('Edit', '/', 'Edit', false); 

            $referer = $this->request->getHTTPReferer();
            $this->view->setVar("referer", $referer);

            $form = new ItemsTypeForm(new ItemsType(), array('edit' => true));
            $this->view->setVar("form", $form);

            $this->view->setVar("name", $type->name);

        }
    }

    /**
     * Creates a new items_type
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $itemsType = new ItemsType();

        $itemsType->code = $this->request->getPost("code");
        $itemsType->name = $this->request->getPost("name");
        

        if (!$itemsType->save()) {
            foreach ($itemsType->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "new"
            ));
        }

        $this->flash->success("Items Type was created successfully");

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));

    }

    /**
     * Saves a items_type edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $type_id = $this->request->getPost("type_id");

        $itemsType = ItemsType::findFirstBytype_id($type_id);
        if (!$itemsType) {
            $this->flash->error("Items Type does not exist " . $type_id);

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $itemsType->code = $this->request->getPost("code");
        $itemsType->name = $this->request->getPost("name");
        

        if (!$itemsType->save()) {

            foreach ($itemsType->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "edit",
                "params" => array($itemsType->type_id)
            ));
        }

        $this->flash->success("Items Type was updated successfully");

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));

    }

    /**
     * Deletes a items_type
     *
     * @param string $type_id
     */
    public function deleteAction($type_id)
    {

        $type = ItemsType::findFirstBytype_id($type_id);
        if (!$type) {
            $this->flash->error("Items Type was not found");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        if (!$type->delete()) {

            foreach ($type->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $this->flash->success("Items Type was deleted successfully");

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));
    }

}
