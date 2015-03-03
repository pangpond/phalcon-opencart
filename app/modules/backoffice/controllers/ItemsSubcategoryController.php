<?php
namespace Modules\Settings\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phoenix\Models\ItemsSubcategory;
use Phoenix\Models\ItemsCategory;
use Phoenix\Models\ItemsType;


use Phalcon\Tag,
    Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Select,
    Phalcon\Forms\Element\TextArea;

use Phoenix\Forms\ItemsSubcategoryForm;

use Phoenix\Controllers\ControllerBase;

class itemsSubcategoryController extends ControllerBase
{
    protected $defaultIndexLimit = 3000;
    protected $defaultPageLimit = 10;
    protected $defaultNullData = 'N/A';

    public function initialize()
    {
        global $config;
        Tag::setTitle('Items Subcategory');
        $this->loadCustomTrans('settings/items_subcategory');
        parent::initialize();

        $this->crumbs->add('settings', $config->application->baseUri.'settings', 'Settings'); 
        
        $this->crumbs->add('items_subcategory', $config->application->baseUri.'settings/items_subcategory', 'Items Subcategory');
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

        $itemsSubcategory = ItemsSubcategory::find(array(
            "order" => "subcat_id ASC",
            "limit" => $this->defaultIndexLimit,
        ));

        if (count($itemsSubcategory) == 0) {
            $this->flash->notice("The search did not find any items");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $itemsSubcategory,
            "limit"=> $this->defaultPageLimit,
            "page" => $numberPage
        ));

        $form = new ItemsSubcategoryForm(new ItemsSubcategory(), array('search' => true));
        $this->view->setVar("form", $form);

        $this->view->page = $paginator->getPaginate();


        $this->crumbs->add('items_subcategory', '/', 'Items Subcategory', false); 
        $this->view->setVar('searchType', 'index');
        $this->view->setVar('defaultNullData', $this->defaultNullData);
    }

    public function basicSearchAction()
    {
        global $config;

        $numberPage = 1;
        if ($this->request->isPost()) {

          $parameters['conditions'] = ' cat_id LIKE :cat_id:
                                        OR type_id LIKE :type_id:
                                        OR code LIKE :code:
                                        OR name LIKE :name:';

          $parameters['bind'] =  array (
                                    'cat_id' => '%' . $_POST['keyword'] . '%',
                                    'type_id' => '%' . $_POST['keyword'] . '%',
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

        $parameters["order"] = "subcat_id";

        $itemsSubcategory = ItemsSubcategory::find($parameters);

        if (count($itemsSubcategory) == 0) {
            $this->flash->notice("The search did not find any type");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $itemsSubcategory,
            "limit"=> $this->defaultPageLimit,
            "page" => $numberPage
        ));
        
        if(!empty($this->persistent->keyword))
            $this->view->setVar('keyword', $this->persistent->keyword);

        $this->view->page = $paginator->getPaginate();
        $this->view->setVar('searchType', 'basicsearch');
        $this->view->pick(array("items_subcategory/search"));

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
            $query = Criteria::fromInput($this->di, "Phoenix\Models\itemsSubcategory", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "subcat_id";

        $itemsSubcategory = ItemsSubcategory::find($parameters);
        if (count($itemsSubcategory) == 0) {
            $this->flash->notice("The search did not find any type");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $itemsSubcategory,
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

        $form = new ItemsSubcategoryForm(new ItemsSubcategory(), array('new' => true));
        $this->view->setVar("form", $form);
    }

    /**
     * Edits a items_subcategory
     *
     * @param string $subcat_id
     */
    public function editAction($subcat_id)
    {

        global $config;
        if (!$this->request->isPost()) {

            $subcat = ItemsSubcategory::findFirstBysubcat_id($subcat_id);
            if (!$subcat) {
                $this->flash->error("item was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "items",
                    "action" => "index"
                ));
            }

            $this->view->subcat_id = $subcat->subcat_id;

            $this->tag->setDefault("subcat_id", $subcat->subcat_id);
            $this->tag->setDefault("cat_id", $subcat->cat_id);
            $this->tag->setDefault("type_id", $subcat->type_id);
            $this->tag->setDefault("code", $subcat->code);
            $this->tag->setDefault("name", $subcat->name);

            if(empty($this->crumbs->crumbs['Edit']['label']))
                $this->crumbs->add('Edit', '/', 'Edit', false); 

            $referer = $this->request->getHTTPReferer();
            $this->view->setVar("referer", $referer);

            $form = new ItemsSubcategoryForm(new ItemsSubcategory(), array('edit' => true));
            $this->view->setVar("form", $form);

            $this->view->setVar("cat_id", $subcat->cat_id);
            $this->view->setVar("type_id", $subcat->type_id);
            $this->view->setVar("code", $subcat->code);
            $this->view->setVar("name", $subcat->name);

        }
    }

    /**
     * Creates a new Items_subcategory
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $itemsSubcategory = new ItemsSubcategory();

        $itemsSubcategory->cat_id = $this->request->getPost("cat_id");
        $itemsSubcategory->type_id = $this->request->getPost("type_id");
        $itemsSubcategory->code = $this->request->getPost("code");
        $itemsSubcategory->name = $this->request->getPost("name");
        

        if (!$itemsSubcategory->save()) {
            foreach ($itemsSubcategory->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "new"
            ));
        }

        $this->flash->success("Items Subcategory was created successfully");

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));

    }

    /**
     * Saves a items_subcategory edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $subcatId = $this->request->getPost("subcat_id");

        $itemsSubcategory = ItemsSubcategory::findFirstBysubcatId($subcatId);
        if (!$itemsSubcategory) {
            $this->flash->error("Items Subcategory does not exist " . $subcatId);

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $itemsSubcategory->cat_id = $this->request->getPost("cat_id");
        $itemsSubcategory->type_id = $this->request->getPost("type_id");
        $itemsSubcategory->code = $this->request->getPost("code");
        $itemsSubcategory->name = $this->request->getPost("name");
        

        if (!$itemsSubcategory->save()) {

            foreach ($itemsSubcategory->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "edit",
                "params" => array($itemsSubcategory->subcat_id)
            ));
        }

        $this->flash->success("Items Subcategory was updated successfully");

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));

    }

    /**
     * Deletes a items_subcategory
     *
     * @param string $subcat_id
     */
    public function deleteAction($subcat_id)
    {

        $subcat = ItemsSubcategory::findFirstBysubcat_id($subcat_id);
        if (!$subcat) {
            $this->flash->error("Items Subcategory was not found");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        if (!$subcat->delete()) {

            foreach ($subcat->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $this->flash->success("Items Subcategory was deleted successfully");

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));
    }

}
