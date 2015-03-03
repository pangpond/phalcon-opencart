<?php
namespace Modules\Settings\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phoenix\Models\ItemsType;
use Phoenix\Models\ItemsCategory;

use Phalcon\Tag,
    Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Select,
    Phalcon\Forms\Element\TextArea;

use Phoenix\Forms\ItemsCategoryForm;

use Phoenix\Controllers\ControllerBase;

class itemsCategoryController extends ControllerBase
{
    protected $defaultIndexLimit = 100;
    protected $defaultPageLimit = 10;
    protected $defaultNullData = 'N/A';

    public function initialize()
    {
        global $config;
        Tag::setTitle('Items Category');
        $this->loadCustomTrans('settings/items_category');
        parent::initialize();

        $this->crumbs->add('settings', $config->application->baseUri.'settings', 'Settings'); 
        
        $this->crumbs->add('items_category', $config->application->baseUri.'settings/items_category', 'Items Category');
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

        $itemsCategory = ItemsCategory::find(array(
            "order" => "cat_id ASC",
            "limit" => $this->defaultIndexLimit,
        ));

        if (count($itemsCategory) == 0) {
            $this->flash->notice("The search did not find any items");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $itemsCategory,
            "limit"=> $this->defaultPageLimit,
            "page" => $numberPage
        ));

        $form = new ItemsCategoryForm(new ItemsCategory(), array('search' => true));
        $this->view->setVar("form", $form);

        $this->view->page = $paginator->getPaginate();


        $this->crumbs->add('items_category', '/', 'Items Category', false); 
        $this->view->setVar('searchType', 'index');
        $this->view->setVar('defaultNullData', $this->defaultNullData);
    }

    public function basicSearchAction()
    {
        global $config;

        $numberPage = 1;
        if ($this->request->isPost()) {

          $parameters['conditions'] = 'type_id LIKE :type_id:
                                        OR code LIKE :code:
                                        OR name LIKE :name:';

          $parameters['bind'] =  array (
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

        $parameters["order"] = "cat_id";

        $itemsCategory = ItemsCategory::find($parameters);

        if (count($itemsCategory) == 0) {
            $this->flash->notice("The search did not find any type");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $itemsCategory,
            "limit"=> $this->defaultPageLimit,
            "page" => $numberPage
        ));
        
        if(!empty($this->persistent->keyword))
            $this->view->setVar('keyword', $this->persistent->keyword);

        $this->view->page = $paginator->getPaginate();
        $this->view->setVar('searchType', 'basicsearch');
        $this->view->pick(array("items_category/search"));

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
            $query = Criteria::fromInput($this->di, "Phoenix\Models\itemsCategory", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "cat_id";

        $itemsCategory = ItemsCategory::find($parameters);
        if (count($itemsCategory) == 0) {
            $this->flash->notice("The search did not find any type");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $itemsCategory,
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

        $form = new ItemsCategoryForm(new ItemsCategory(), array('new' => true));
        $this->view->setVar("form", $form);
    }

    /**
     * Edits a items_category
     *
     * @param string $cat_id
     */
    public function editAction($cat_id)
    {

        global $config;
        if (!$this->request->isPost()) {

            $cat = ItemsCategory::findFirstBycat_id($cat_id);
            if (!$cat) {
                $this->flash->error("item was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "items",
                    "action" => "index"
                ));
            }

            $this->view->cat_id = $cat->cat_id;

            $this->tag->setDefault("cat_id", $cat->cat_id);
            $this->tag->setDefault("type_id", $cat->type_id);
            $this->tag->setDefault("code", $cat->code);
            $this->tag->setDefault("name", $cat->name);

            if(empty($this->crumbs->crumbs['Edit']['label']))
                $this->crumbs->add('Edit', '/', 'Edit', false); 

            $referer = $this->request->getHTTPReferer();
            $this->view->setVar("referer", $referer);

            $form = new ItemsCategoryForm(new ItemsCategory(), array('edit' => true));
            $this->view->setVar("form", $form);

            $this->view->setVar("type_id", $cat->type_id);
            $this->view->setVar("code", $cat->code);
            $this->view->setVar("name", $cat->name);

        }
    }

    /**
     * Creates a new items_category
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $itemsCategory = new ItemsCategory();
        
        $itemsCategory->type_id = $this->request->getPost("type_id");
        $itemsCategory->code = $this->request->getPost("code");
        $itemsCategory->name = $this->request->getPost("name");
        

        if (!$itemsCategory->save()) {
            foreach ($itemsCategory->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "new"
            ));
        }

        $this->flash->success("items Category was created successfully");

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));

    }

    /**
     * Saves a items_category edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $cat_id = $this->request->getPost("cat_id");

        $itemsCategory = ItemsCategory::findFirstBycat_id($cat_id);
        if (!$itemsCategory) {
            $this->flash->error("items Category does not exist " . $cat_id);

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $itemsCategory->type_id = $this->request->getPost("type_id");
        $itemsCategory->code = $this->request->getPost("code");
        $itemsCategory->name = $this->request->getPost("name");
        

        if (!$itemsCategory->save()) {

            foreach ($itemsCategory->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "edit",
                "params" => array($itemsCategory->cat_id)
            ));
        }

        $this->flash->success("items Category was updated successfully");

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));

    }

    /**
     * Deletes a items_category
     *
     * @param string $cat_id
     */
    public function deleteAction($cat_id)
    {

        $type = ItemsCategory::findFirstBycat_id($cat_id);
        if (!$type) {
            $this->flash->error("items Category was not found");

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

        $this->flash->success("items Category was deleted successfully");

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));
    }

}
