<?php

namespace Phoenix\Controllers;

use Phalcon\Mvc\Model\Criteria,
    Phalcon\Tag;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Mvc\View;

use Phalcon\Image\Adapter\Imagick;

use Phoenix\Models\Events;
// use Phoenix\Models\EventsMeta;
use Phoenix\Models\Geography;
use Phoenix\Models\Province;
use Phoenix\Models\District;
use Phoenix\Models\Subdistrict;
use Phoenix\Models\EventsParticipateInfo;



use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Select,
    Phalcon\Forms\Element\TextArea;


use Phoenix\Forms\EventsForm;

use Phoenix\Library\PHPExcel\Classes\PHPExcel;


// use Phoenix\Library\PHPExcel\Classes\PHPExcel\PHPExcel as PHPExcel;


class EventsController extends ControllerBase
{


    public function initialize()
    {
        Tag::setTitle('Events');
        $this->loadCustomTrans('events');
        parent::initialize();

        $this->uploadLocation = $this->uploadBaseLocation . 'events/';

        $this->view->setVar('defaultCurrency', $this->defaultCurrency);
        $this->crumbs->add('events', $this->applicationConfig->baseUri.'events', 'Events');
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

        $academy = Events::find(array(
            "order" => "event_id DESC",
            // "limit" => $this->defaultIndexLimit,
        ));

        if (count($academy) == 0) {
            $this->flash->notice($this->controllerTranslate["The search did not find any events"]);
        }

        $paginator = new Paginator(array(
            "data" => $academy,
            "limit"=> $this->defaultPageLimit,
            "page" => $numberPage
        ));

        $form = new EventsForm(new Events(), array('search' => true));
        $this->view->setVar("form", $form);

        $page = $paginator->getPaginate();
        $this->view->setVar('page', $page);
        
        $this->view->setVar('searchType', 'index');
        $this->view->setVar('defaultNullData', $this->defaultNullData);
        $this->view->setVar('title', 'Event List');
        $this->crumbs->add('events', '', 'Events', false);


    }

    public function basicSearchAction()
    {
        global $config;

        $numberPage = 1;
        if ($this->request->isPost()) {

          $parameters['conditions'] = 'name LIKE :name:
                                        OR detail LIKE :detail:
                                        OR venues LIKE :venues:
                                        OR organizer LIKE :organizer:';

          $parameters['bind'] =  array (
                                    'name' => '%' . $_POST['keyword'] . '%',
                                    'detail' => '%' . $_POST['keyword'] . '%',
                                    'venues' => '%' . $_POST['keyword'] . '%',
                                    'organizer' => '%' . $_POST['keyword'] . '%',
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

        $parameters["order"] = "event_id";

        $event = Events::find($parameters);

        if (count($event) == 0) {
            $this->flash->notice($this->controllerTranslate["The search did not find any event"]);

            return $this->dispatcher->forward(array(
                "controller" => "events",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $event,
            "limit"=> 10,
            "page" => $numberPage
        ));
        
        if(!empty($this->persistent->keyword))
            $this->view->setVar('keyword', $this->persistent->keyword);

        $this->view->page = $paginator->getPaginate();
        $this->view->setVar('searchType', 'basicsearch');
        $this->view->pick(array("events/search"));

        $this->view->setVar('defaultNullData', $this->defaultNullData);

        $this->crumbs->add('events', $config->application->baseUri.'events', 'Events');
        $this->crumbs->add('Search', '/', 'Search result(s)', false); 
    }

    /**
     * Searches for member
     */
    public function searchAction()
    {
        global $config;
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Phoenix\Models\Events", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "event_id";

        $event = Events::find($parameters);
        if (count($event) == 0) {
            $this->flash->notice($this->controllerTranslate["The search did not find any member"]);

            return $this->dispatcher->forward(array(
                "controller" => "events",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $event,
            "limit"=> 10,
            "page" => $numberPage
        ));

        // if(!empty($this->persistent->keyword))
        //     $this->view->setVar('keyword', $this->persistent->keyword);

        $this->view->page = $paginator->getPaginate();
        $this->view->setVar('searchType', 'search');

        $this->view->setVar('defaultNullData', $this->defaultNullData);

        $this->crumbs->add('events', $config->application->baseUri.'events', 'Events');
        $this->crumbs->add('Search', '/', 'Search result(s)', false); 

    }

    /**
     * Displayes the creation form
     */
    public function newAction()
    {

        $this->crumbs->add('events', $config->application->baseUri.'events', 'Events');
        if(empty($this->crumbs->crumbs['new']['label']))
            $this->crumbs->add('New', '/', 'Add', false); 

        $form = new EventsForm(new Events(), array('new' => true));
        $this->view->setVar("form", $form);
    }

    /**
     * Edits a Events
     *
     * @param string $event_id
     */
    public function editAction($event_id)
    {
        global $config;
        if (!$this->request->isPost()) {

            $event = Events::findFirstByevent_id($event_id);
            if (!$event) {
                $this->flash->error($this->controllerTranslate["Event was not found"]);

                return $this->dispatcher->forward(array(
                    "controller" => "events",
                    "action" => "index"
                ));
            }

            $this->view->event_id = $event->event_id;

            $this->tag->setDefault("event_id", $event->event_id);
            $this->tag->setDefault("name", $event->name);
            $this->tag->setDefault("detail", $event->detail);
            $this->tag->setDefault("venues", $event->venues);
            $this->tag->setDefault("start", $event->start);
            $this->tag->setDefault("end", $event->end);
            $this->tag->setDefault("organizer", $event->organizer);

            $this->crumbs->add('events', $config->application->baseUri.'events', 'Events');
            if(empty($this->crumbs->crumbs['Edit']['label']))
                $this->crumbs->add('Edit', '/', 'Edit', false); 

            $referer = $this->request->getHTTPReferer();
            $this->view->setVar("referer", $referer);

            $form = new EventsForm(new Events(), array('edit' => true));
            $this->view->setVar("form", $form);

            $this->view->setVar("event", $event);

            $elementPrefix = $this->elements->getPrefixNameData();
            $this->view->setVar("name", $event->name);

        }
    }

    public function createEvents($eventData)
    {
        $event = new Events();

        $event->name = $eventData['name'];
        $event->detail = $eventData['detail'];
        $event->venues = $eventData['venues'];
        $event->start = $eventData['start'];
        $event->end = $eventData['end'];
        $event->organizer = $eventData['organizer'];

        // $event->lastupdated = date('Y-m-d H:i:s');

        if (!$event->save()) {
            foreach ($event->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "events",
                "action" => "new"
            ));
        }

        return $event->event_id;

    }

    public function createEventsMeta($eventId, $eventMetaData)
    {
        $metaArray = $eventMetaData['meta'];

        if ($this->request->hasFiles() == true) {

            if(isset($_FILES['image'])){
                foreach ($this->request->getUploadedFiles() as $file){
                    //Move the file into the application
                    $file->moveTo($this->uploadLocation . $file->getName());
                    $path = pathinfo($this->uploadLocation . $file->getName());
                    
                    $filename = $this->uploadLocation . $eventId . '.' . $path['extension'];
                    rename($this->uploadLocation . $path['basename'], $filename);
                }
                $metaArray['image'] = $eventId . '.' . $path['extension'];
            }
        }

        foreach ($metaArray as $metaKey => $metaValue) {
            if(!empty($metaValue)){
                $eventMeta = new Eventsmeta();
                $eventMeta->event_id = $eventId;
                $eventMeta->meta_key = $metaKey;
                $eventMeta->meta_value = $metaValue;

                if (!$eventMeta->save()) {
                    foreach ($eventMeta->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                        return $this->dispatcher->forward(array(
                        "controller" => "events",
                        "action" => "new"
                    ));
                }
            }
        }

        return true;

    }

    public function viewAction($event_id)
    {
        global $config;

        $event = Events::findFirstByevent_id($event_id);
        // var_dump($event);
        if (!$event) {
            $this->flash->error($this->controllerTranslate["Event was not found"]);

            return $this->dispatcher->forward(array(
                "controller" => "events",
                "action" => "index"
            ));
        }

        $this->view->event_id = $event->event_id;

        //set default meta
        $eventMetaArray['note'] = $eventMetaArray['mate'] = $eventMetaArray['retired'] = $this->defaultNullData;

        $eventMetaArray['image'] = $this->defaultEventImage;

        foreach ($event->eventsMeta as $eventMeta) {
            if($eventMeta->meta_key == 'image')
                $eventMetaArray[$eventMeta->meta_key] =  $this->uploadLocation . $eventMeta->meta_value;
            else
                $eventMetaArray[$eventMeta->meta_key] = $eventMeta->meta_value;
        }

        $this->view->memberMeta = $eventMetaArray;

  
        if(empty($this->crumbs->crumbs['View']['label']))
            $this->crumbs->add('View', '/', 'View', false); 

        $event->province_name = $event->province->province_name;
        $event->district_name = $event->district->district_name;
        $event->subdistrict_name = $event->subdistrict->subdistrict_name;

        if($event->province_id != 1){
            $event->subdistrict_prefix = 'ตำบล';
            $event->district_prefix = 'อำเภอ';
        }
        else{
            $event->subdistrict_prefix = 'แขวง';
            $event->district_prefix = 'เขต';
        }

        if($event->status){
            if(!empty($event->eventsAcademy->academy_id)){
                $academy = Academy::findFirstByAcademy_id($event->eventsAcademy->academy_id);

                $academy->province_name = $academy->province->province_name;
                $academy->district_name = $academy->district->district_name;
                $academy->subdistrict_name = $academy->subdistrict->subdistrict_name;
            }
            if($academy->province_id != 1){
                $academy->subdistrict_prefix = 'ตำบล';
                $academy->district_prefix = 'อำเภอ';
            }
            else{
                $academy->subdistrict_prefix = 'แขวง';
                $academy->district_prefix = 'เขต';
            }
            $this->view->setVar("academy", $academy);
        }

        $elementPrefix =$this->elements->getPrefixNameData();
        $this->view->setVar("name", $elementPrefix[$event->title] . ' ' . $event->firstname . ' ' . $event->lastname );
        $this->view->setVar("member", $event);
        
    }
    /**
     * Creates a new Events
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "events",
                "action" => "new",
            ));
        }

        if($eventId = $this->createEvents($this->request->getPost())){
            $this->flash->success($this->controllerTranslate["Event was created successfully"]);

            return $this->dispatcher->forward(array(
                "controller" => "events",
                "action" => "index"
            ));
        }

        return $this->dispatcher->forward(array(
            "controller" => "events",
            "action" => "new"
        ));

    }

    /**
     * Saves a Events edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "events",
                "action" => "index"
            ));
        }

        $event_id = $this->request->getPost("event_id");

        $event = Events::findFirstByevent_id($event_id);
        if (!$event) {
            $this->flash->error($this->controllerTranslate["Event does not exist"]);

            return $this->dispatcher->forward(array(
                "controller" => "events",
                "action" => "index"
            ));
        }

        $event->code = $this->request->getPost('name');
        $event->detail = $this->request->getPost('detail');
        $event->venues = $this->request->getPost('venues');
        $event->start = $this->request->getPost('start');
        $event->end = $this->request->getPost('end');
        $event->organizer = $this->request->getPost('organizer');

        if (!$event->save()) {
            foreach ($event->getMessages() as $message) {
                $this->flash->error($message);
            }
        }

        $this->flash->success($this->controllerTranslate["Event was updated successfully"]);

        return $this->dispatcher->forward(array(
            "controller" => "events",
            "action" => "index"
        ));

    }

    /**
     * Deletes a Events
     *
     * @param string $event_id
     */
    public function deleteAction($event_id)
    {

        $Events = Events::findFirstByevent_id($event_id);
        if (!$Events) {
            $this->flash->error($this->controllerTranslate["Event was not found"]);

            return $this->dispatcher->forward(array(
                "controller" => "Events",
                "action" => "index"
            ));
        }

        if (!$Events->delete()) {

            foreach ($Events->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "Events",
                "action" => "search"
            ));
        }

        $this->flash->success($this->controllerTranslate["Event was deleted successfully"]);

        return $this->dispatcher->forward(array(
            "controller" => "Events",
            "action" => "index"
        ));
    }

    function participateAction($eventId){
        global $config;

        if(!empty($eventId)){
            $this->view->disable();

            $queryParticipateProvince = "SELECT distinct province_id FROM Phoenix\Models\EventsParticipateInfo ORDER BY province_id ASC";

            $participateProvince = $this->modelsManager->executeQuery($queryParticipateProvince);

            //define html template path
            define('REPORT_TEMPLATE_PATH', $config->application->viewsDir . 'reportTemplates/');
            define('REPORT_PDF_PATH', 'report/event');

            $this->dompdf->set_base_path( REPORT_TEMPLATE_PATH );
            $this->dompdf->set_paper('a4'); 

            // read content from html template file
            $reportTemplate = file_get_contents(REPORT_TEMPLATE_PATH . $reportFile = 'events-participate.html', FILE_USE_INCLUDE_PATH);

            $reportContent = '';
            foreach ($participateProvince as $province) {

                $participate = EventsParticipateInfo::find(array(
                    "event_id = $eventId AND province_id = $province->province_id",
                    "order" => "province_id"
                ));

                $reportContentArray[$province->province_id] = file_get_contents(REPORT_TEMPLATE_PATH . $reportFile = 'events-participate-body.html', FILE_USE_INCLUDE_PATH);

                $row = '';
                $rowNumber = 1;
                foreach ($participate as $event) {
                    $elementPrefix =$this->elements->getPrefixNameData();
                    $eventTitle = $elementPrefix[$event->title];

                    $row .= '<tr>
                                <td class="text-center xl" >' . $rowNumber . '</td>
                                <td class="xl">' . $eventTitle . ' ' . $event->firstname .' ' . $event->lastname . '</td>
                            </tr>';
                    $rowNumber++;
                }

                $reportContentArray[$event->province_id] = str_replace('{{ROW}}', $row, $reportContentArray[$event->province_id]);
                $reportContentArray[$event->province_id] = str_replace('{{PROVINCE}}', $event->province_name, $reportContentArray[$event->province_id]);

                 $reportContent .= $reportContentArray[$event->province_id];
            }

            $reportTemplate = str_replace('{{BODY}}', $reportContent, $reportTemplate);

            // generate PDF
            $this->dompdf->load_html($reportTemplate);
            $this->dompdf->render();
            $this->dompdf->stream($reportCode = "event" . ".pdf", array('Attachment'=>0, 'compress'=>1));
        }


    }


}