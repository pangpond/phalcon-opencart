<?php
namespace Modules\Settings\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phoenix\Models\ItemsGroup;

use Phalcon\Tag,
    Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Select,
    Phalcon\Forms\Element\TextArea;

use Phoenix\Forms\ItemsGroupForm;

use Phoenix\Controllers\ControllerBase;

class ItemsGroupController extends ControllerBase
{
    protected $defaultIndexLimit = 2000;
    protected $defaultPageLimit = 10;
    protected $defaultNullData = 'N/A';

    public function initialize()
    {
        global $config;
        Tag::setTitle('Items Group');
        $this->loadCustomTrans('settings/items_group');
        parent::initialize();

        $this->crumbs->add('settings', $config->application->baseUri.'settings', 'Settings'); 
        
        $this->crumbs->add('items_group', $config->application->baseUri.'settings/items_group', 'Items Group');
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

        $itemsGroup = ItemsGroup::find(array(
            "order" => "group_id ASC",
            "limit" => $this->defaultIndexLimit,
        ));

        if (count($itemsGroup) == 0) {
            $this->flash->notice("The search did not find any items");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $itemsGroup,
            "limit"=> $this->defaultPageLimit,
            "page" => $numberPage
        ));

        $form = new ItemsGroupForm(new ItemsGroup(), array('search' => true));
        $this->view->setVar("form", $form);

        $this->view->page = $paginator->getPaginate();


        $this->crumbs->add('items_group', '/', 'Items Group', false); 
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

        $parameters["order"] = "group_id";

        $ItemsGroup = ItemsGroup::find($parameters);

        if (count($ItemsGroup) == 0) {
            $this->flash->notice("The search did not find any type");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $ItemsGroup,
            "limit"=> $this->defaultPageLimit,
            "page" => $numberPage
        ));
        
        if(!empty($this->persistent->keyword))
            $this->view->setVar('keyword', $this->persistent->keyword);

        $this->view->page = $paginator->getPaginate();
        $this->view->setVar('searchType', 'basicsearch');
        $this->view->pick(array("items_group/search"));

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
            $query = Criteria::fromInput($this->di, "Phoenix\Models\ItemsGroup", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "group_id";

        $ItemsGroup = ItemsGroup::find($parameters);
        if (count($ItemsGroup) == 0) {
            $this->flash->notice("The search did not find any type");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $ItemsGroup,
            "limit"=> $this->defaultPageLimit,
            "page" => $numberPage
        ));

        // if(!empty($this->persistent->keyword))
        //     $this->view->setVar('keyword', $this->persistent->keyword);

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

        $form = new ItemsGroupForm(new ItemsGroup(), array('new' => true));
        $this->view->setVar("form", $form);
    }

    /**
     * Edits a items_group
     *
     * @param string $group_id
     */
    public function editAction($group_id)
    {

        global $config;
        if (!$this->request->isPost()) {

            $type = ItemsGroup::findFirstBygroup_id($group_id);
            if (!$type) {
                $this->flash->error("item was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "items",
                    "action" => "index"
                ));
            }

            $this->view->group_id = $type->group_id;

            $this->tag->setDefault("group_id", $type->group_id);
            $this->tag->setDefault("code", $type->code);
            $this->tag->setDefault("name", $type->name);

            if(empty($this->crumbs->crumbs['Edit']['label']))
                $this->crumbs->add('Edit', '/', 'Edit', false); 

            $referer = $this->request->getHTTPReferer();
            $this->view->setVar("referer", $referer);

            $form = new ItemsGroupForm(new ItemsGroup(), array('search' => true));
            $this->view->setVar("form", $form);

            $this->view->setVar("name", $type->name);

        }
    }

    /**
     * Creates a new items_group
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "items_group",
                "action" => "index"
            ));
        }

        $ItemsGroup = new ItemsGroup();

        $ItemsGroup->code = $this->request->getPost("code");
        $ItemsGroup->name = $this->request->getPost("name");
        

        if (!$ItemsGroup->save()) {
            foreach ($ItemsGroup->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "items_group",
                "action" => "new"
            ));
        }

        $this->flash->success("Items Group was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "items_group",
            "action" => "index"
        ));

    }

    /**
     * Saves a items_group edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $group_id = $this->request->getPost("group_id");

        $ItemsGroup = ItemsGroup::findFirstBygroup_id($group_id);
        if (!$ItemsGroup) {
            $this->flash->error("Items Group does not exist " . $group_id);

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $ItemsGroup->code = $this->request->getPost("code");
        $ItemsGroup->name = $this->request->getPost("name");
        

        if (!$ItemsGroup->save()) {

            foreach ($ItemsGroup->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "edit",
                "params" => array($ItemsGroup->group_id)
            ));
        }

        $this->flash->success("Items Group was updated successfully");

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));

    }

    /**
     * Deletes a items_group
     *
     * @param string $group_id
     */
    public function deleteAction($group_id)
    {

        $type = ItemsGroup::findFirstBygroup_id($group_id);
        if (!$type) {
            $this->flash->error("Items Group was not found");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        if (!$type->delete()) {

            foreach ($type->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "search"
            ));
        }

        $this->flash->success("Items Group was deleted successfully");

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));
    }

}
