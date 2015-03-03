<?php
namespace Modules\Backoffice\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phoenix\Models\Academicyears;

use Phalcon\Tag,
    Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Select,
    Phalcon\Forms\Element\TextArea;

use Phoenix\Forms\AcademicyearsForm;

use Phoenix\Controllers\ControllerBase;

class AcademicyearsController extends ControllerBase
{
    protected $defaultIndexLimit = 30;
    protected $defaultPageLimit = 10;
    protected $defaultNullData = 'N/A';

    public function initialize()
    {
        global $config;
        Tag::setTitle('Academic Year');
        $this->loadCustomTrans('settings/academicyears');
        parent::initialize();

        $this->crumbs->add('settings', $config->application->baseUri.'settings', 'Settings'); 
        
        $this->crumbs->add('academicyears', $config->application->baseUri.'settings/academicyears', 'AcademicYear');
    }

    /**
     * Index action
     */
    public function indexAction()
    {

        $this->persistent->parameters = null;

        // $numberPage = $this->request->getQuery("page", "int");
        // if(empty($numberPage))
        //     $numberPage = 1;

        // $academicyears = Academicyears::find(array(
        //     "order" => "academicyear_id ASC",
        //     "limit" => $this->defaultIndexLimit,
        // ));

        // if (count($academicyears) == 0) {
        //     $this->flash->notice("The search did not find any items");

        //     return $this->dispatcher->forward(array(
        //         "action" => "index"
        //     ));
        // }

        // $paginator = new Paginator(array(
        //     "data" => $academicyears,
        //     "limit"=> $this->defaultPageLimit,
        //     "page" => $numberPage
        // ));

        // $form = new AcademicyearsForm(new Academicyears(), array('search' => true));
        // $this->view->setVar("form", $form);

        // $this->view->page = $paginator->getPaginate();


        $this->crumbs->add('academicyears', '/', 'Academicyears', false); 
        $this->view->setVar('searchType', 'index');
        $this->view->setVar('defaultNullData', $this->defaultNullData);
    }

    public function basicSearchAction()
    {
        global $config;

        $numberPage = 1;
        if ($this->request->isPost()) {

          $parameters['conditions'] = 'name LIKE :name:
                                        OR term LIKE :term:
                                        OR start LIKE :start:
                                        OR end LIKE :end:';

          $parameters['bind'] =  array (
                                    'name' => '%' . $_POST['keyword'] . '%',
                                    'term' => '%' . $_POST['keyword'] . '%',
                                    'start' => '%' . $_POST['keyword'] . '%',
                                    'end' => '%' . $_POST['keyword'] . '%',
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

        $parameters["order"] = "academicyear_id";

        $academicyears = Academicyears::find($parameters);

        if (count($academicyears) == 0) {
            $this->flash->notice("The search did not find any type");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $academicyears,
            "limit"=> $this->defaultPageLimit,
            "page" => $numberPage
        ));
        
        if(!empty($this->persistent->keyword))
            $this->view->setVar('keyword', $this->persistent->keyword);

        $this->view->page = $paginator->getPaginate();
        $this->view->setVar('searchType', 'basicsearch');
        $this->view->pick(array("academicyears/search"));

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
            $query = Criteria::fromInput($this->di, "Phoenix\Models\Academicyears", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "academicyear_id";

        $academicyears = Academicyears::find($parameters);
        if (count($academicyears) == 0) {
            $this->flash->notice("The search did not find any type");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $academicyears,
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

        $form = new AcademicyearsForm(new Academicyears(), array('new' => true));
        $this->view->setVar("form", $form);
    }

    /**
     * Edits a academicyears
     *
     * @param string $type_id
     */
    public function editAction($academicyearId)
    {

        global $config;
        if (!$this->request->isPost()) {

            $academicyear = Academicyears::findFirstByacademicyearId($academicyearId);
            if (!$academicyear) {
                $this->flash->error("item was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "items",
                    "action" => "index"
                ));
            }

            $this->view->academicyearId = $academicyear->academicyear_id;

            $this->tag->setDefault("academicyearId", $academicyear->academicyear_id);
            $this->tag->setDefault("name", $academicyear->name);
            $this->tag->setDefault("term", $academicyear->term);
            $this->tag->setDefault("start", $academicyear->start);
            $this->tag->setDefault("end", $academicyear->end);

            if(empty($this->crumbs->crumbs['Edit']['label']))
                $this->crumbs->add('Edit', '/', 'Edit', false); 

            $referer = $this->request->getHTTPReferer();
            $this->view->setVar("referer", $referer);

            $form = new AcademicyearsForm(new Academicyears(), array('search' => true));
            $this->view->setVar("form", $form);

            $this->view->setVar("name", $academicyear->name);
            $this->view->setVar("term", $academicyear->term);
            $this->view->setVar("start", $academicyear->start);
            $this->view->setVar("end", $academicyear->end);

        }
    }

    /**
     * Creates a new academicyears
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "academicyears",
                "action" => "index"
            ));
        }

        $academicyears = new Academicyears();

        $academicyears->name = $this->request->getPost("name");
        $academicyears->term = $this->request->getPost("term");
        $academicyears->start = $this->request->getPost("start");
        $academicyears->end = $this->request->getPost("end");
        

        if (!$academicyears->save()) {
            foreach ($academicyears->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "academicyears",
                "action" => "new"
            ));
        }

        $this->flash->success("Academicyears was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "academicyears",
            "action" => "index"
        ));

    }

    /**
     * Saves a academicyears edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $academicyearId = $this->request->getPost("academicyearId");

        $academicyears = Academicyears::findFirstByacademicyearId($academicyearId);
        if (!$academicyears) {
            $this->flash->error("Academicyears does not exist " . $academicyearId);

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $academicyears->name = $this->request->getPost("name");
        $academicyears->term = $this->request->getPost("term");
        $academicyears->start = $this->request->getPost("start");
        $academicyears->end = $this->request->getPost("end");
        

        if (!$academicyears->save()) {

            foreach ($academicyears->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "edit",
                "params" => array($academicyears->academicyear_id)
            ));
        }

        $this->flash->success("Academicyears was updated successfully");

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));

    }

    /**
     * Deletes a academicyears
     *
     * @param string $type_id
     */
    public function deleteAction($academicyearId)
    {

        $academicyear = Academicyears::findFirstByacademicyearId($academicyearId);
        if (!$academicyear) {
            $this->flash->error("Academic year was not found");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        if (!$academicyear->delete()) {

            foreach ($academicyear->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "search"
            ));
        }

        $this->flash->success("Academic Year was deleted successfully");

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));
    }

}
