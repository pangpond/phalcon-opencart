<?php

namespace Phoenix\Controllers;

use Phalcon\Mvc\Model\Criteria,
    Phalcon\Tag;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Image\Adapter\Imagick;

use Phoenix\Models\Users;

use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Select,
    Phalcon\Forms\Element\TextArea;


use Phoenix\Forms\ItemsForm,
    Phoenix\Forms\ItemsCopyForm;

// use Phoenix\Forms\MembersForm;

class GuestController extends ControllerBase
{

    protected $defaultStatus = 1;
    protected $defaultIndexLimit = 30;
    protected $defaultPageLimit = 10;
    protected $defaultNullData = 'N/A';
    protected $defaultRegisteredDate = '0000-00-00 00:00:00';
    protected $defaultItemImage = 'img/items-book.jpg';
    protected $defaultItemType = '7';
    protected $defaultItemCategory = '1';
    protected $defaultItemSubCategory = '1';
    protected $defaultItemGroup = '251';
    protected $defaultItemLanguage = '1';
    protected $defaultCurrency = 'THB';
    // protected $baseLocation = 'uploads/items/';


    public function initialize()
    {
        Tag::setTitle('Items');
        $this->loadCustomTrans('guest');
        parent::initialize();


        $language = $this->session->get("language");
        if (!$language) {
            $this->session->set("language", "cn");
        }

        if($language == 'en'){
            $currentLanguage = Tag::image("img/demo/flags/us.gif") . '<span>US</span>';
            $this->session->set("language", "en");
        }
        elseif($language == 'th'){
            $currentLanguage = Tag::image("img/demo/flags/th.gif") . '<span>TH</span>';
            $this->session->set("language", "th");
        }
        elseif($language == 'cn'){
            $currentLanguage = Tag::image("img/demo/flags/cn.gif") . '<span>CN</span>';
            $this->session->set("language", "cn");
        }
        $this->view->setVar('currentLanguage', $currentLanguage);

        $this->view->setVar('defaultCurrency', $this->defaultCurrency);
        $this->crumbs->add('members', $this->applicationConfig->baseUri.'guest', 'Members');
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;

        
        $this->view->setVar('searchType', 'index');
        $this->view->setVar('defaultNullData', $this->defaultNullData);
        $this->view->setVar('title', 'Member List');

        $this->crumbs->add('members', '', 'Members', false);

    }

    public function basicSearchAction()
    {
        global $config;

        $numberPage = 1;
        if ($this->request->isPost()) {

          $parameters['conditions'] = 'name LIKE :name:
                                        OR author LIKE :author:
                                        OR publisher LIKE :publisher:
                                        OR publish LIKE :publish:';

          $parameters['bind'] =  array (
                                    'name' => '%' . $_POST['keyword'] . '%',
                                    'author' => '%' . $_POST['keyword'] . '%',
                                    'publisher' => '%' . $_POST['keyword'] . '%',
                                    'publish' => '%' . $_POST['keyword'] . '%',
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

        $parameters["order"] = "item_id";

        $items = Items::find($parameters);

        if (count($items) == 0) {
            $this->flash->notice("The search did not find any items");

            return $this->dispatcher->forward(array(
                "controller" => "guest",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $items,
            "limit"=> 10,
            "page" => $numberPage
        ));
        
        if(!empty($this->persistent->keyword))
            $this->view->setVar('keyword', $this->persistent->keyword);

        $this->view->page = $paginator->getPaginate();
        $this->view->setVar('searchType', 'basicsearch');
        $this->view->pick(array("guest/search"));

        $this->view->setVar('defaultNullData', $this->defaultNullData);

        $this->crumbs->add('items', $config->application->baseUri.'items', 'Items');
        $this->crumbs->add('Search', '/', 'Search result(s)', false); 
    }

    /**
     * Searches for items
     */
    public function searchAction()
    {
        global $config;
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Phoenix\Models\Items", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "item_id";

        $items = Items::find($parameters);
        if (count($items) == 0) {
            $this->flash->notice("The search did not find any items");

            return $this->dispatcher->forward(array(
                "controller" => "guest",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $items,
            "limit"=> 10,
            "page" => $numberPage
        ));

        // if(!empty($this->persistent->keyword))
        //     $this->view->setVar('keyword', $this->persistent->keyword);

        $this->view->page = $paginator->getPaginate();
        $this->view->setVar('searchType', 'search');

        $this->view->setVar('defaultNullData', $this->defaultNullData);

        $this->crumbs->add('items', $config->application->baseUri.'items', 'Items');
        $this->crumbs->add('Search', '/', 'Search result(s)', false); 

    }

    public function viewAction($item_id)
    {
        global $config;

        $this->loadCustomTrans('items');

        $item = Items::findFirstByitem_id($item_id);
        // var_dump($item);
        if (!$item) {
            $this->flash->error("item was not found");

            return $this->dispatcher->forward(array(
                "controller" => "guest",
                "action" => "index"
            ));
        }

        $this->view->item_id = $item->item_id;

        //set default meta
        $itemMetaArray['print'] = $itemMetaArray['language'] = $itemMetaArray['volume'] = $itemMetaArray['page'] = $itemMetaArray['cost'] = $itemMetaArray['town'] = $itemMetaArray['sponsor'] = $itemMetaArray['vendor'] = $itemMetaArray['note'] = $itemMetaArray['abstract'] = $itemMetaArray['tag'] = $this->defaultNullData;

        $itemMetaArray['image'] = $this->defaultItemImage;

        $item->identity = empty($item->identity) ? $defaultNullData : $item->identity;

        foreach ($item->itemsMeta as $itemMeta) {
            if($itemMeta->meta_key == 'image')
                $itemMetaArray[$itemMeta->meta_key] =  $this->baseLocation . $itemMeta->meta_value;
            else if($itemMeta->meta_key == 'print' && !empty($itemMeta->meta_value) && !is_null($itemMeta->meta_value) && $itemMeta->meta_value != 'null'){
                $itemMetaPrint = json_decode($itemMeta->meta_value);
                $itemMetaArray['print'] = $itemMetaPrint;
            }
            else
                $itemMetaArray[$itemMeta->meta_key] = $itemMeta->meta_value;
        }

        if($itemMetaArray['cost'] != $this->defaultNullData)
            $itemMetaArray['cost'] = number_format($itemMetaArray['cost'], 2);

        if(!is_object($itemMetaArray['print'])){
            $itemMetaArray['print'] = (object) array('year' => '-', 'month' => '-', 'count' => '-');
        }

        $this->view->itemMeta = $itemMetaArray;

        $this->crumbs->add('items', $config->application->baseUri.'items', 'Items');
            if(empty($this->crumbs->crumbs['edit']['label']))
                $this->crumbs->add('View', '/', 'View', false); 

        if($item->cat_id){
            $itemCategory = ItemsCategory::findFirstByCatId($item->cat_id); 
            $item->category = $itemCategory->name;
        }
        else
            $item->category = $this->defaultNullData;

        if($item->type_id){
            $itemType = ItemsType::findFirstByTypeId($item->type_id); 
            $item->type = $itemType->name;
        }
        else
            $item->type = $this->defaultNullData;

        if($item->subcat_id){
            $itemSubCategory = ItemsSubcategory::findFirstBySubcatId($item->subcat_id); 
            $item->subCategory = $itemSubCategory->name;
        }
        else
            $item->subCategory = $this->defaultNullData;

        if($item->group_id){
            $itemGroup = ItemsGroup::findFirstByGroupId($item->group_id); 
            $item->group = $itemGroup->name;
        }
        else
            $item->group = $this->defaultNullData;


        $elementItemLanguage =$this->elements->getItemLanguage();
        $item->item_language = $elementItemLanguage[$itemMetaArray['language']];

        $this->crumbs->add('items', '', 'Items', true);
        $this->view->setVar("item", $item);
    }

    public function bookAction()
    {
        $this->persistent->parameters = null;

        $numberPage = $this->request->getQuery("page", "int");
        if(empty($numberPage))
            $numberPage = 1;

        $items = Items::find(array(
            "type_id <= 7",
            "order" => "item_id DESC",
            "limit" => $this->defaultIndexLimit,
        ));

        if (count($items) == 0) {
            $this->flash->notice("The search did not find any items");

            return $this->dispatcher->forward(array(
                "controller" => "guest",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $items,
            "limit"=> $this->defaultPageLimit,
            "page" => $numberPage
        ));

        $form = new ItemsForm(new Items(), array('search' => true));
        $this->view->setVar("form", $form);

        // $this->view->page = $paginator->getPaginate();
        $page = $paginator->getPaginate();
        $this->view->setVar('page', $page);
        $this->view->setVar('searchType', 'index');
        $this->view->setVar('defaultNullData', $this->defaultNullData);
        $this->view->setVar('title', 'Book List');

        $this->crumbs->add('book', '', 'Book', false);

        $this->view->pick(array('items/index'));
    }

    public function journalAction()
    {
        $this->persistent->parameters = null;

        $numberPage = $this->request->getQuery("page", "int");
        if(empty($numberPage))
            $numberPage = 1;

        $items = Items::find(array(
            "type_id in ('8', '9')",
            "order" => "item_id DESC",
            "limit" => $this->defaultIndexLimit,
        ));

        if (count($items) == 0) {
            $this->flash->notice("The search did not find any items");

            return $this->dispatcher->forward(array(
                "controller" => "guest",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $items,
            "limit"=> $this->defaultPageLimit,
            "page" => $numberPage
        ));

        $form = new ItemsForm(new Items(), array('search' => true));
        $this->view->setVar("form", $form);

        // $this->view->page = $paginator->getPaginate();
        $page = $paginator->getPaginate();
        $this->view->setVar('page', $page);
        $this->view->setVar('searchType', 'index');
        $this->view->setVar('defaultNullData', $this->defaultNullData);
        $this->view->setVar('title', 'Journal List');

        $this->crumbs->add('journal', '', 'Journal', false);

        $this->view->pick(array('items/index'));
    }

    public function mediaAction()
    {
        $this->persistent->parameters = null;

        $numberPage = $this->request->getQuery("page", "int");
        if(empty($numberPage))
            $numberPage = 1;

        $items = Items::find(array(
            "type_id >= 11",
            "order" => "item_id DESC",
            "limit" => $this->defaultIndexLimit,
        ));

        if (count($items) == 0) {
            $this->flash->notice("The search did not find any items");

            return $this->dispatcher->forward(array(
                "controller" => "guest",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $items,
            "limit"=> $this->defaultPageLimit,
            "page" => $numberPage
        ));

        $form = new ItemsForm(new Items(), array('search' => true));
        $this->view->setVar("form", $form);

        // $this->view->page = $paginator->getPaginate();
        $page = $paginator->getPaginate();
        $this->view->setVar('page', $page);
        $this->view->setVar('searchType', 'index');
        $this->view->setVar('defaultNullData', $this->defaultNullData);
        $this->view->setVar('title', 'Media List');

        $this->crumbs->add('media', '', 'Media', false);

        $this->view->pick(array('items/index'));
    }

    public function registerAction()
    {
        $this->tag->setDefault("registered", date('Y-m-d H:i:s'));
        $this->tag->setDefault("status", $this->defaultStatus);
        $this->view->setVar("memberMeta", array('image' => $this->defaultMemberImage));
        // $form = new MembersForm(new Members(), array('new' => true));
        // $this->view->setVar("form", $form);
    }

}
