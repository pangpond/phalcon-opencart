<?php

namespace Phoenix\Controllers;

use Phalcon\Mvc\Model\Criteria,
    Phalcon\Tag;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Image\Adapter\Imagick;

use Phoenix\Models\Academy;
use Phoenix\Models\AcademyMeta;
use Phoenix\Models\Geography;
use Phoenix\Models\Province;
use Phoenix\Models\District;
use Phoenix\Models\Subdistrict;
use Phoenix\Models\Area;
use Phoenix\Models\Divisions;

use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Select,
    Phalcon\Forms\Element\TextArea;


use Phoenix\Forms\AcademyForm;

class AcademyController extends ControllerBase
{

    protected $defaultAcademyImage = 'img/academy.jpg';
    protected $defaultAcademyType = 1;
    protected $uploadLocation;


    public function initialize()
    {
        Tag::setTitle('Academy');
        $this->loadCustomTrans('academy');
        parent::initialize();

        $this->uploadLocation = $this->uploadBaseLocation . 'academy/';

        $this->view->setVar('defaultCurrency', $this->defaultCurrency);
        $this->crumbs->add('academy', $this->applicationConfig->baseUri.'academy', 'Academy');
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

        $academy = Academy::find(array(
            "order" => "academy_id DESC",
            // "limit" => $this->defaultIndexLimit,
        ));

        if (count($academy) == 0) {
            $this->flash->notice($this->controllerTranslate["The search did not find any academy"]);
        }

        $paginator = new Paginator(array(
            "data" => $academy,
            "limit"=> $this->defaultPageLimit,
            "page" => $numberPage
        ));

        $form = new AcademyForm(new Academy(), array('search' => true));
        $this->view->setVar("form", $form);

        $page = $paginator->getPaginate();
        $this->view->setVar('page', $page);
        
        $this->view->setVar('searchType', 'index');
        $this->view->setVar('defaultNullData', $this->defaultNullData);
        $this->view->setVar('title', 'Academy List');

        $this->crumbs->add('academy', '', 'Academy', false);


    }

    /**
     * Index action
     */
    public function areaAction($areaId = 1)
    {
        $this->persistent->parameters = null;

        $numberPage = $this->request->getQuery("page", "int");
        if(empty($numberPage))
            $numberPage = 1;

        $conditions = '';
        if(isset($areaId) && !empty($areaId) && is_integer($areaId))
            $conditions = 'area_id = ' . $areaId;

        $academy = Academy::find(array(
            "conditions"=>  "area_id = $areaId",
            "order" => "academy_id DESC",
            // "limit" => $this->defaultIndexLimit,
        ));


        if (count($academy) == 0) {
            $this->flash->notice($this->controllerTranslate["The search did not find any academy"]);
        }

        $paginator = new Paginator(array(
            "data" => $academy,
            "limit"=> $this->defaultPageLimit,
            "page" => $numberPage
        ));

        $form = new AcademyForm(new Academy(), array('search' => true));
        $this->view->setVar("form", $form);

        $page = $paginator->getPaginate();
        $this->view->setVar('page', $page);
        
        $this->view->setVar('searchType', 'area/'. $areaId);
        $this->view->setVar('defaultNullData', $this->defaultNullData);
        $this->view->setVar('title', 'Academy By Area');
        $this->view->setVar('area_id', $areaId);

        $this->crumbs->add('area', '/', 'By Area', false); 

    }

    /**
     * Index action
     */
    public function provinceAction($provinceId = 1)
    {
        $this->persistent->parameters = null;

        $numberPage = $this->request->getQuery("page", "int");
        if(empty($numberPage))
            $numberPage = 1;

        $conditions = '';
        if(isset($provinceId) && !empty($provinceId) && is_integer($provinceId))
            $conditions = 'province_id = ' . $provinceId;

        $academy = Academy::find(array(
            "conditions"=>  "province_id = $provinceId",
            "order" => "academy_id DESC",
            // "limit" => $this->defaultIndexLimit,
        ));


        if (count($academy) == 0) {
            $this->flash->notice($this->controllerTranslate["The search did not find any academy"]);
        }

        $paginator = new Paginator(array(
            "data" => $academy,
            "limit"=> $this->defaultPageLimit,
            "page" => $numberPage
        ));

        $form = new AcademyForm(new Academy(), array('search' => true));
        $this->view->setVar("form", $form);

        $page = $paginator->getPaginate();
        $this->view->setVar('page', $page);
        
        $this->view->setVar('searchType', 'province/'. $provinceId);
        $this->view->setVar('defaultNullData', $this->defaultNullData);
        $this->view->setVar('title', 'Academy By Province');
        $this->view->setVar('province_id', $provinceId);

         $this->crumbs->add('province', '/', 'By Province', false); 

    }

    public function grabProvinceAction()
    {
        $this->view->disable();

        $id = $this->request->getPost("id", "int");

        $data = Province::find(array(
            "columns"   =>  array("province_id, province_name"),
            "conditions"=>  "geo_id = :id:",
            "bind"      =>  array("id"=>$id),
            "order"     => "province_name"
        ));

        foreach ($data as $result) {
            $resData[] = array("id"=>$result->province_id, "name"=>$result->province_name);
        }

        echo json_encode($resData);
    }

    public function grabDistrictAction()
    {
        $this->view->disable();

        $id = $this->request->getPost("id", "int");

        $data = District::find(array(
            "columns"   =>  array("district_id, district_name"),
            "conditions"=>  "province_id = :id:",
            "bind"      =>  array("id"=>$id),
            "order"     => "district_name"
        ));

        foreach ($data as $result) {
            $resData[] = array("id"=>$result->district_id, "name"=>$result->district_name);
        }

        echo json_encode($resData);
    }

    public function grabSubDistrictAction()
    {
        $this->view->disable();

        $id = $this->request->getPost("id", "int");

        $data = Subdistrict::find(array(
            "columns"   =>  array("subdistrict_id, subdistrict_name"),
            "conditions"=>  "district_id = :id:",
            "bind"      =>  array("id"=>$id),
            "order"     => "subdistrict_name"
        ));

        foreach ($data as $result) {
            $resData[] = array("id"=>$result->subdistrict_id, "name"=>$result->subdistrict_name);
        }

        echo json_encode($resData);
    }

    public function grabAcademiesAction()
    {
        $this->view->disable();

        $id = $this->request->getPost("id", "int");

        $data = Academy::find(array(
            "columns"   =>  array("academy_id, name"),
            "conditions"=>  "area_id = :id:",
            "bind"      =>  array("id"=>$id),
            "order"     => "name"
        ));

        foreach ($data as $result) {
            $resData[] = array("id"=>$result->academy_id, "name"=>$result->name);
        }

        echo json_encode($resData);
    }

    public function grabAreaAction()
    {
        $this->view->disable();

        $id = $this->request->getPost("id", "int");

        $area = Area::findFirst(array(
            "conditions"=>  "area_id = :id:",
            "bind"      =>  array("id"=>$id),
        ));


        $area->province_name = $area->province->province_name;
        $area->district_name = $area->district->district_name;
        $area->subdistrict_name = $area->subdistrict->subdistrict_name;

        if($area->province_id != 1){
            $area->subdistrict_prefix = 'ตำบล';
            $area->district_prefix = 'อำเภอ';
        }
        else{
            $area->subdistrict_prefix = 'แขวง';
            $area->district_prefix = 'เขต';
        }

        echo json_encode($area);
    }

    public function grabAcademyAction()
    {
        $this->view->disable();

        $id = $this->request->getPost("id", "int");

        $academy = Academy::findFirst(array(
            "conditions"=>  "academy_id = :id:",
            "bind"      =>  array("id"=>$id),
        ));

        $academy->province_name = $academy->province->province_name;
        $academy->district_name = $academy->district->district_name;
        $academy->subdistrict_name = $academy->subdistrict->subdistrict_name;

        if($academy->province_id != 1){
            $academy->subdistrict_prefix = 'ตำบล';
            $academy->district_prefix = 'อำเภอ';
        }
        else{
            $academy->subdistrict_prefix = 'แขวง';
            $academy->district_prefix = 'เขต';
        }

        if(is_null($academy->address))
            $academy->address = '';

        echo json_encode($academy);
    }

    public function basicSearchAction()
    {
        global $config;

        $numberPage = 1;
        if ($this->request->isPost()) {

          $parameters['conditions'] = 'code LIKE :code:
                                        OR name LIKE :name:
                                        OR address LIKE :address:
                                        OR telephone LIKE :telephone:';

          $parameters['bind'] =  array (
                                    'code' => '%' . $_POST['keyword'] . '%',
                                    'name' => '%' . $_POST['keyword'] . '%',
                                    'address' => '%' . $_POST['keyword'] . '%',
                                    'telephone' => '%' . $_POST['keyword'] . '%',
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

        $parameters["order"] = "academy_id";

        $academy = Academy::find($parameters);

        if (count($academy) == 0) {
            $this->flash->notice($this->controllerTranslate["The search did not find any academy"]);

            return $this->dispatcher->forward(array(
                "controller" => "academy",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $academy,
            "limit"=> 10,
            "page" => $numberPage
        ));
        
        if(!empty($this->persistent->keyword))
            $this->view->setVar('keyword', $this->persistent->keyword);

        $this->view->page = $paginator->getPaginate();
        $this->view->setVar('searchType', 'basicsearch');
        $this->view->pick(array("academy/search"));

        $this->view->setVar('defaultNullData', $this->defaultNullData);

        $this->crumbs->add('academy', $config->application->baseUri.'academy', 'Academy');
        $this->crumbs->add('Search', '/', 'Search result(s)', false); 
    }

    /**
     * Searches for academy
     */
    public function searchAction()
    {
        global $config;
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Phoenix\Models\Academy", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "academy_id";

        $academy = Academy::find($parameters);
        if (count($academy) == 0) {
            $this->flash->notice($this->controllerTranslate["The search did not find any academy"]);

            return $this->dispatcher->forward(array(
                "controller" => "academy",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $academy,
            "limit"=> 10,
            "page" => $numberPage
        ));

        // if(!empty($this->persistent->keyword))
        //     $this->view->setVar('keyword', $this->persistent->keyword);

        $this->view->page = $paginator->getPaginate();
        $this->view->setVar('searchType', 'search');

        $this->view->setVar('defaultNullData', $this->defaultNullData);

        $this->crumbs->add('academy', $config->application->baseUri.'academy', 'Academy');
        $this->crumbs->add('Search', '/', 'Search result(s)', false); 

    }

    /**
     * Displayes the creation form
     */
    public function newAction()
    {
        global $config;
        // $this->tag->setDefault("registered", date('Y-m-d H:i:s'));
        // $this->tag->setDefault("status", $this->defaultStatus);
        // $this->tag->setDefault("type", $this->defaultAcademyType);

        $this->view->setVar("academyMeta", array('image' => $this->defaultAcademyImage));

        $this->crumbs->add('academy', $config->application->baseUri.'academy', 'Academy');
        if(empty($this->crumbs->crumbs['new']['label']))
            $this->crumbs->add('New', '/', 'Add', false); 

        $form = new AcademyForm(new Academy(), array('new' => true));
        $this->view->setVar("form", $form);

        // $prefixName = json_encode($this->elements->getPrefixNameData());
        // $this->view->setVar("prefixName", $prefixName);
    }

    /**
     * Edits a Academy
     *
     * @param string $academy_id
     */
    public function editAction($academy_id)
    {
        global $config;
        if (!$this->request->isPost()) {

            $academy = Academy::findFirstByacademy_id($academy_id);
            if (!$academy) {
                $this->flash->error($this->controllerTranslate["Academy was not found"]);

                return $this->dispatcher->forward(array(
                    "controller" => "academy",
                    "action" => "index"
                ));
            }

            $this->view->academy_id = $academy->academy_id;

            $this->tag->setDefault("academy_id", $academy->academy_id);
            $this->tag->setDefault("code", $academy->code);
            $this->tag->setDefault("name", $academy->name);
            $this->tag->setDefault("address", $academy->address);
            $this->tag->setDefault("address2", $academy->address2);
            $this->tag->setDefault("zipcode", $academy->zipcode);
            $this->tag->setDefault("email", $academy->email);
            $this->tag->setDefault("telephone", $academy->telephone);
            $this->tag->setDefault("fax", $academy->fax);
            $this->tag->setDefault("smis_code", $academy->smis_code);
            $this->tag->setDefault("ministry_code", $academy->ministry_code);
            $this->tag->setDefault("division_id", $academy->division_id);


            $this->view->setVar("academyMeta", array('image' => $this->defaultAcademyImage));

            $academyMetaRows = AcademyMeta::find("academy_id = " . $academy->academy_id);
            foreach ($academyMetaRows as $academyMeta) {
                $this->tag->setDefault('meta['. $academyMeta->meta_key . ']', $academyMeta->meta_value);

                if($academyMeta->meta_key == 'image')
                    $this->view->setVar("academyMeta", array('image' => $this->uploadLocation . $academyMeta->meta_value));
            }

            $academy_id = $academy->academy_id;
            $area_id = $academy->area_id;

            $this->crumbs->add('academy', $config->application->baseUri.'academy', 'Academy');
            if(empty($this->crumbs->crumbs['Edit']['label']))
                $this->crumbs->add('Edit', '/', 'Edit', false); 

            $referer = $this->request->getHTTPReferer();
            $this->view->setVar("referer", $referer);

            $form = new AcademyForm(new Academy(), array('edit' => true));
            $this->view->setVar("form", $form);

            $this->view->setVar("academy", $academy);

            $this->view->setVar("academy_id", $academy_id);
            $this->view->setVar("area_id", $area_id);

            $elementPrefix =$this->elements->getPrefixNameData();
            $this->view->setVar("name", $academy->name );

        }
    }

    public function createAcademy($academyData)
    {
        $academy = new Academy();

        $academy->code = $academyData['code'];
        $academy->name = $academyData['name'];
        $academy->smis_code = $academyData['smis_code'];
        $academy->ministry_code = $academyData['ministry_code'];
        $academy->division_id = $academyData['division_id'];
        $academy->address = $academyData['address'];
        $academy->address2 = $academyData['address2'];
        $academy->province_id = $academyData['province'];
        $academy->district_id = $academyData['district'];
        $academy->subdistrict_id = $academyData['subdistrict'];
        $academy->zipcode = $academyData['zipcode'];
        $academy->email = $academyData['email'];
        $academy->telephone = $academyData['telephone'];
        $academy->fax = $academyData['fax'];
        $academy->last_updated = date('Y-m-d H:i:s');
        $academy->area_id = $academyData['area_id'];

        

        if (!$academy->save()) {
            foreach ($academy->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "academy",
                "action" => "new"
            ));
        }

        return $academy->academy_id;

    }

    public function createAcademyMeta($academyId, $academyMetaData)
    {
        $metaArray = $academyMetaData['meta'];

        if ($this->request->hasFiles() == true) {

            if(isset($_FILES['image'])){
                foreach ($this->request->getUploadedFiles() as $file){
                    //Move the file into the application
                    $file->moveTo($this->uploadLocation . $file->getName());
                    $path = pathinfo($this->uploadLocation . $file->getName());
                    
                    $filename = $this->uploadLocation . $academyId . '.' . $path['extension'];
                    rename($this->uploadLocation . $path['basename'], $filename);
                }
                $metaArray['image'] = $academyId . '.' . $path['extension'];
            }
        }

        foreach ($metaArray as $metaKey => $metaValue) {
            if(!empty($metaValue)){
                $academyMeta = new Academymeta();
                $academyMeta->academy_id = $academyId;
                $academyMeta->meta_key = $metaKey;
                $academyMeta->meta_value = $metaValue;

                if (!$academyMeta->save()) {
                    foreach ($academyMeta->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                        return $this->dispatcher->forward(array(
                        "controller" => "academy",
                        "action" => "new"
                    ));
                }
            }
        }

        return true;

    }

    public function createAreaDirector($academyId, $areaId)
    {
        $areaDirector = new AreaDirector();

        $areaDirector->academy_id = $academyId;
        $areaDirector->area_id = $areaId;

        if (!$areaDirector->save()) {
            foreach ($academyMeta->getMessages() as $message) {
                $this->flash->error($message);
            }
                return $this->dispatcher->forward(array(
                "controller" => "academy",
                "action" => "new"
            ));
        }

        return true;
    }

    public function viewAction($academy_id)
    {
        global $config;

        $academy = Academy::findFirstByacademy_id($academy_id);
        // var_dump($academy);
        if (!$academy) {
            $this->flash->error($this->controllerTranslate["Academy was not found"]);

            return $this->dispatcher->forward(array(
                "controller" => "academy",
                "action" => "index"
            ));
        }

        $this->view->academy_id = $academy->academy_id;

        //set default meta
        $academyMetaArray['note'] = $academyMetaArray['student-middle'] = $academyMetaArray['student-high'] = $academyMetaArray['student-ep'] = $academyMetaArray['student-mep'] = $academyMetaArray['student-gifted'] = $academyMetaArray['website']  = $this->defaultNullData;



        $academyMetaArray['image'] = $this->defaultAcademyImage;

        foreach ($academy->academyMeta as $academyMeta) {
            if($academyMeta->meta_key == 'image')
                $academyMetaArray[$academyMeta->meta_key] =  $this->uploadLocation . $academyMeta->meta_value;
            else
                $academyMetaArray[$academyMeta->meta_key] = $academyMeta->meta_value;
        }

        $this->view->academyMeta = $academyMetaArray;

  
        if(empty($this->crumbs->crumbs['View']['label']))
            $this->crumbs->add('View', '/', 'View', false); 

        $academy->province_name = $academy->province->province_name;
        $academy->district_name = $academy->district->district_name;
        $academy->subdistrict_name = $academy->subdistrict->subdistrict_name;

        if($academy->province_id != 1){
            $academy->subdistrict_prefix = 'ตำบล';
            $academy->district_prefix = 'อำเภอ';
        }
        else{
            $academy->subdistrict_prefix = 'แขวง';
            $academy->district_prefix = 'เขต';
        }

        if(empty($academy->smis_code))
            $academy->smis_code = $this->defaultNullData;

        if(empty($academy->ministry_code))
            $academy->ministry_code = $this->defaultNullData;

        $academy->province_name = $academy->province->province_name;
        $academy->district_name = $academy->district->district_name;
        $academy->subdistrict_name = $academy->subdistrict->subdistrict_name;

        if($academy->province_id != 1){
            $academy->subdistrict_prefix = 'ตำบล';
            $academy->district_prefix = 'อำเภอ';
        }
        else{
            $academy->subdistrict_prefix = 'แขวง';
            $academy->district_prefix = 'เขต';
        }
        
        $division = Divisions::findFirstByDivision_id($academy->division_id);

        $elementPrefix =$this->elements->getPrefixNameData();
        $this->view->setVar("name", $academy->name );
        $this->view->setVar("academy", $academy);
        $this->view->setVar("divisionName", $division->division_name);
    }
    /**
     * Creates a new Academy
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "academy",
                "action" => "new",
            ));
        }

        if($academyId = $this->createAcademy($this->request->getPost())){
            $this->flash->success($this->controllerTranslate["Academy was created successfully"]);

            if($this->createAcademyMeta($academyId, $this->request->getPost()))
                $this->flash->success($this->controllerTranslate["Academy Meta was created successfully"]);

            return $this->dispatcher->forward(array(
                "controller" => "academy",
                "action" => "index"
            ));
        }

        return $this->dispatcher->forward(array(
            "controller" => "academy",
            "action" => "new"
        ));

    }

    /**
     * Saves a Academy edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "academy",
                "action" => "index"
            ));
        }

        $academy_id = $this->request->getPost("academy_id");

        $academy = Academy::findFirstByacademy_id($academy_id);
        if (!$academy) {
            $this->flash->error($this->controllerTranslate["Academy does not exist"]);

            return $this->dispatcher->forward(array(
                "controller" => "academy",
                "action" => "index"
            ));
        }

        $academy->academy_id = $this->request->getPost('academy_id');
        $academy->code = $this->request->getPost('code');
        $academy->name = $this->request->getPost('name');
        $academy->smis_code = $this->request->getPost('smis_code');
        $academy->ministry_code = $this->request->getPost('ministry_code');
        $academy->division_id = $this->request->getPost('division_id');
        $academy->address = $this->request->getPost('address');
        $academy->address2 = $this->request->getPost('address2');
        $academy->geo_id = $this->request->getPost('geo');
        $academy->province_id = $this->request->getPost('province');
        $academy->district_id = $this->request->getPost('district');
        $academy->subdistrict_id = $this->request->getPost('subdistrict');
        $academy->zipcode = $this->request->getPost('zipcode');
        $academy->email = $this->request->getPost('email');
        $academy->telephone = $this->request->getPost('telephone');
        $academy->fax = $this->request->getPost('fax');
        $academy->last_updated = date('Y-m-d H:i:s');
        $academy->area_id = $this->request->getPost('area_id');


        if (!$academy->save()) {

            foreach ($academy->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->view->setVar("academyMeta", array('image' => $this->defaultAcademyImage));

            $academyMetaRows = Academymeta::find("academy_id = " . $academy->academy_id);
            foreach ($academyMetaRows as $academyMeta) {
                $this->tag->setDefault('meta['. $academyMeta->meta_key . ']', $academyMeta->meta_value);

                if($academyMeta->meta_key == 'image')
                    $this->view->setVar("memberMeta", array('image' => $this->uploadLocation . $academyMeta->meta_value));
            }
            
            $this->view->academy_id = $academy->academy_id;
            $form = new AcademyForm(new Academy(), array('edit' => true));
            $this->view->setVar("form", $form);
            $elementPrefix =$this->elements->getPrefixNameData();
            $this->view->setVar("name", $academy->name );

            
            return $this->dispatcher->forward(array(
                "controller" => "academy",
                "action" => "edit",
                "params" => array($academy->academy_id)
            ));
        }

        $this->flash->success($this->controllerTranslate["Academy was updated successfully"]);

        //Academy Meta
        $metaArray = $this->request->getPost("meta");

        if ($this->request->hasFiles() == true) {
            if(isset($_FILES['image'])){
                
                foreach ($this->request->getUploadedFiles() as $file){
                    //Move the file into the application
                    $file->moveTo($this->uploadLocation . $file->getName());
                    $path = pathinfo($this->uploadLocation . $file->getName());
                    
                    $filename = $this->uploadLocation . $academy_id . '.' . $path['extension'];
                    rename($this->uploadLocation . $path['basename'], $filename);

                }
                $metaArray['image'] = $academy_id . '.' . $path['extension'];
            }
        }

        foreach ($metaArray as $metaKey => $metaValue) {
            if(!empty($metaValue)){

                $academyMeta = Academymeta::findFirst(array(
                    'academy_id = :academy_id: and meta_key = :meta_key:',
                    'bind' => array(    
                        'academy_id' => $academy_id,
                        'meta_key' => $metaKey,
                    )
                ));

                if (!$academyMeta) {// Insert new Meta
                    $academyMeta = new Academymeta();
                    $academyMeta->academy_id = $academy->academy_id;
                    $academyMeta->meta_key = $metaKey;
                    $academyMeta->meta_value = $metaValue;

                    if (!$academyMeta->save()) {
                        foreach ($academyMeta->getMessages() as $message) {
                            $this->flash->error($message);
                        }
                    }
                }
                else{//Update Meta
                    $academyMeta->academy_id = $academy->academy_id;
                    $academyMeta->meta_key = $metaKey;
                    $academyMeta->meta_value = $metaValue;

                    if (!$academyMeta->save()) {
                        foreach ($academyMeta->getMessages() as $message) {
                            $this->flash->error($message);
                        }
                    }
                }
            }
        }

        return $this->dispatcher->forward(array(
            "controller" => "academy",
            "action" => "index"
        ));

    }

    /**
     * Deletes a Academy
     *
     * @param string $academy_id
     */
    public function deleteAction($academy_id)
    {

        $Academy = Academy::findFirstByacademy_id($academy_id);
        if (!$Academy) {
            $this->flash->error($this->controllerTranslate["Academy was not found"]);

            return $this->dispatcher->forward(array(
                "controller" => "Academy",
                "action" => "index"
            ));
        }

        if (!$Academy->delete()) {

            foreach ($Academy->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "Academy",
                "action" => "index"
            ));
        }

        $this->flash->success($this->controllerTranslate["Academy was deleted successfully"]);

        return $this->dispatcher->forward(array(
            "controller" => "Academy",
            "action" => "index"
        ));
    }

    public function getAcademyAction()
    {
        global $config;

        $this->view->disable();

        if ($this->request->isPost() == true) {

            // Check whether the request was made with Ajax
            if ($this->request->isAjax() == true) {
                $this->response->setContentType('application/json', 'UTF-8');

                $copyId = $this->request->getPost("academy_id");
                // $academyId = $this->request->getPost("academy_id");

                $academyCopy = AcademyCopy::findFirstBycopy_id($copyId);
                if (!$academyCopy) {
                    $data = array(
                        'status' => 'unsuccess',
                        'msg' => 'Academy Copy not found'
                    );

                    $response->setContent(json_encode($data));
                    return $response;
                }

                $academy = Academy::findFirstByacademy_id($academyCopy->academy_id);
                if (!$academy) {
                    $data = array(
                        'status' => 'unsuccess',
                        'msg' => 'Academy not found'
                    );

                    $response->setContent(json_encode($data));
                    return $response;
                }

                $academyStatus = ($academy->status) ? '<span class="label label-satgreen">' . $this->mainTranslate["Active"] . '</span>' : '<span class="label label-lightred">' . $this->mainTranslate["Inactive"] . '</span>';

                $conditions = "academy_id = :academy_id: AND meta_key = :meta_key:";
                $parameters = array(
                    "academy_id" => $academy->academy_id,
                    "meta_key" => "image"
                );

                $academyMeta = Academymeta::findFirst(array(
                    $conditions,
                    "bind" => $parameters
                ));

                if($academyMeta)
                    $academyImage = $this->tag->image(array($this->uploadLocation.$academyMeta->meta_value)); #$this->uploadLocation.$academyMeta->meta_value;
                else
                    $academyImage = $this->tag->image(array($this->defaultAcademyImage));

                $response = new \Phalcon\Http\Response();

                $data = array(
                    'status' => 'success',
                    'itemCopyId' => $academyCopy->copy_id, 
                    'itemCopyNo' => $academyCopy->copy, 
                    'itemId' => $academy->academy_id, 
                    'itemName' => $academy->name,
                    'itemAuthor' => $academy->author,
                    'itemPublisher' => $academy->publisher,
                    'itemIdentity' => $academy->identity,
                    'itemAuthorCode' => $academy->author_code,
                    'itemStatus' => $academyStatus,
                    'itemImage' => $academyImage,
                );
                $response->setContent(json_encode($data));

                return $response;
            }
        }
    }


}