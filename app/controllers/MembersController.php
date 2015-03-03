<?php

namespace Phoenix\Controllers;

use Phalcon\Mvc\Model\Criteria,
    Phalcon\Tag;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Mvc\View;

use Phalcon\Image\Adapter\Imagick;

use Phoenix\Models\Members;
use Phoenix\Models\MembersMeta;
use Phoenix\Models\MembersAcademy;
use Phoenix\Models\MembersLog;
use Phoenix\Models\Geography;
use Phoenix\Models\Province;
use Phoenix\Models\District;
use Phoenix\Models\Subdistrict;
use Phoenix\Models\Area;
use Phoenix\Models\AreaProvince;
use Phoenix\Models\Academy;
use Phoenix\Models\EventsParticipateInfo;
use Phoenix\Models\MembersCard;
use Phoenix\Models\MembersCardBacklog;




use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Select,
    Phalcon\Forms\Element\TextArea;


use Phoenix\Forms\MembersForm;

use Phoenix\Library\PHPExcel\Classes\PHPExcel;


// use Phoenix\Library\PHPExcel\Classes\PHPExcel\PHPExcel as PHPExcel;


class MembersController extends ControllerBase
{

    protected $defaultMemberImage = 'img/teacher-man.png';
    protected $defaultMemberType = 1;
    protected $uploadLocation;


    public function initialize()
    {
        Tag::setTitle('Members');
        $this->loadCustomTrans('members');
        parent::initialize();

        $this->uploadLocation = $this->uploadBaseLocation . 'members/';

        $this->view->setVar('defaultCurrency', $this->defaultCurrency);
        $this->crumbs->add('members', $this->applicationConfig->baseUri.'members', 'Members');
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


        if(isset($this->auth['province']) && !empty($this->auth['province']))
            $provinceId = $this->auth['province'];

        if(isset($this->auth['area']) && !empty($this->auth['area']))
            $areaId = $this->auth['area'];

        if($this->auth['username'] == 'user79'){
            $queryMemberByAcademyProvince = "SELECT m.* FROM Phoenix\Models\Members AS m LEFT JOIN Phoenix\Models\MembersAcademy AS ma ON m.member_id = ma.member_id LEFT JOIN Phoenix\Models\Academy AS a ON a.academy_id = ma.academy_id WHERE a.area_id >=44 ORDER BY m.member_id DESC";

            $member = $this->modelsManager->executeQuery($queryMemberByAcademyProvince);

            $provinceName = ' อบจ.';
        }
        else if(!empty($provinceId)){

            $queryMemberByAcademyProvince = "SELECT m.* FROM Phoenix\Models\Members AS m LEFT JOIN Phoenix\Models\MembersAcademy AS ma ON m.member_id = ma.member_id LEFT JOIN Phoenix\Models\Academy AS a ON a.academy_id = ma.academy_id WHERE a.province_id = " . $provinceId . " ORDER BY m.member_id DESC";

            $member = $this->modelsManager->executeQuery($queryMemberByAcademyProvince);

            // $member = Members::find(array(
            //     "conditions"=>  "province_id = $provinceId",
            //     "order" => "member_id DESC",
            //     "limit" => $this->defaultIndexLimit,
            // ));

            $province = Province::findFirstByprovince_id($provinceId);
            $provinceName = $province->province_name;
        }
        else if(!empty($areaId)){
            $queryMemberByAcademyArea = "SELECT m.* FROM Phoenix\Models\Members AS m LEFT JOIN Phoenix\Models\MembersAcademy AS ma ON m.member_id = ma.member_id LEFT JOIN Phoenix\Models\Academy AS a ON a.academy_id = ma.academy_id WHERE a.area_id = " . $areaId . " ORDER BY m.member_id DESC";

            $member = $this->modelsManager->executeQuery($queryMemberByAcademyArea);

            $area = Area::findFirstByarea_id($areaId);

            $provinceName = ' ' . str_replace('องค์กรปกครองส่วนท้องถิ่น', 'อปท.', $area->name);
        }
        else{
            $member = Members::find(array(
                "order" => "member_id DESC",
                // "limit" => $this->defaultIndexLimit,
            ));
            $provinceName = '';
        }

            

        if (count($member) == 0) {
            $this->flash->notice($this->controllerTranslate["The search did not find any member"]);
        }

        $paginator = new Paginator(array(
            "data" => $member,
            "limit"=> $this->defaultPageLimit,
            "page" => $numberPage
        ));

        $form = new MembersForm(new Members(), array('search' => true));
        $this->view->setVar("form", $form);

        $page = $paginator->getPaginate();
        $this->view->setVar('page', $page);
        
        $this->view->setVar('searchType', 'index');
        $this->view->setVar('defaultNullData', $this->defaultNullData);
        $this->view->setVar('title', 'Member List');
        $this->view->setVar('province', $provinceName);
        $this->crumbs->add('members', '', 'Members', false);
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

        $member = Members::find(array(
            "conditions"=>  "area_id = $areaId",
            "order" => "member_id DESC",
            "limit" => $this->defaultIndexLimit,
        ));

        if (count($member) == 0) {
            $this->flash->notice($this->controllerTranslate["The search did not find any member"]);
        }

        $paginator = new Paginator(array(
            "data" => $member,
            "limit"=> $this->defaultPageLimit,
            "page" => $numberPage
        ));

        $form = new MembersForm(new Members(), array('search' => true));
        $this->view->setVar("form", $form);

        $page = $paginator->getPaginate();
        $this->view->setVar('page', $page);
        
        $this->view->setVar('searchType', 'area/');
        $this->view->setVar('defaultNullData', $this->defaultNullData);
        $this->view->setVar('title', 'Member List');
        $this->view->setVar('area_id', $areaId);

        $this->crumbs->add('members', '', 'Members', false);
    }

    /**
     * Index action
     */
    public function provinceAction($provinceId = 1)
    {
        if($this->auth['role'] == 'M'){
            $this->flash->error("Sorry You don't have access permit to this module");
            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $this->persistent->parameters = null;

        $numberPage = $this->request->getQuery("page", "int");
        if(empty($numberPage))
            $numberPage = 1;

        $conditions = '';
        if(isset($provinceId) && !empty($provinceId) && is_integer($provinceId))
            $conditions = 'province_id = ' . $provinceId;

        $member = Members::find(array(
            "conditions"=>  "province_id = $provinceId",
            "order" => "member_id DESC",
            // "limit" => $this->defaultIndexLimit,
        ));

        // var_dump($member);

        if (count($member) == 0) {
            $this->flash->notice($this->controllerTranslate["The search did not find any member"]);
        }

        $paginator = new Paginator(array(
            "data" => $member,
            "limit"=> $this->defaultPageLimit,
            "page" => $numberPage
        ));

        $form = new MembersForm(new Members(), array('search' => true));
        $this->view->setVar("form", $form);

        $page = $paginator->getPaginate();
        $this->view->setVar('page', $page);
        
        $this->view->setVar('searchType', 'province/');
        $this->view->setVar('defaultNullData', $this->defaultNullData);
        $this->view->setVar('title', 'Members By Province');
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

          $parameters['conditions'] = 'firstname LIKE :firstname:
                                        OR lastname LIKE :lastname:
                                        OR code LIKE :code:
                                        OR telephone LIKE :telephone:';

          $parameters['bind'] =  array (
                                    'firstname' => '%' . $_POST['keyword'] . '%',
                                    'lastname' => '%' . $_POST['keyword'] . '%',
                                    'code' => '%' . $_POST['keyword'] . '%',
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

        $parameters["order"] = "member_id";

        $member = Members::find($parameters);

        if (count($member) == 0) {
            $this->flash->notice($this->controllerTranslate["The search did not find any member"]);

            return $this->dispatcher->forward(array(
                "controller" => "members",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $member,
            "limit"=> 10,
            "page" => $numberPage
        ));
        
        if(!empty($this->persistent->keyword))
            $this->view->setVar('keyword', $this->persistent->keyword);

        $this->view->page = $paginator->getPaginate();
        $this->view->setVar('searchType', 'basicsearch');
        $this->view->pick(array("members/search"));

        $this->view->setVar('defaultNullData', $this->defaultNullData);

        $this->crumbs->add('members', $config->application->baseUri.'members', 'Members');
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
            $query = Criteria::fromInput($this->di, "Phoenix\Models\Members", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "member_id";

        $member = Members::find($parameters);
        if (count($member) == 0) {
            $this->flash->notice($this->controllerTranslate["The search did not find any member"]);

            return $this->dispatcher->forward(array(
                "controller" => "members",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $member,
            "limit"=> 10,
            "page" => $numberPage
        ));

        // if(!empty($this->persistent->keyword))
        //     $this->view->setVar('keyword', $this->persistent->keyword);

        $this->view->page = $paginator->getPaginate();
        $this->view->setVar('searchType', 'search');

        $this->view->setVar('defaultNullData', $this->defaultNullData);

        $this->crumbs->add('members', $config->application->baseUri.'members', 'Members');
        $this->crumbs->add('Search', '/', 'Search result(s)', false); 
    }

    /**
     * Displayes the creation form
     */
    public function newAction()
    {
        global $config;
        $this->tag->setDefault("registered", date('Y-m-d H:i:s'));
        $this->tag->setDefault("status", $this->defaultStatus);
        $this->tag->setDefault("type", $this->defaultMemberType);


        if(isset($this->auth['province']) && !empty($this->auth['province']))
            $area = AreaProvince::findFirstByprovince_id($this->auth['province']);

        else if(isset($this->auth['area']) && !empty($this->auth['area']))
            $area = Area::findFirstByarea_id($this->auth['area']);
        else if($this->auth['role'] == 'A')
            $area = null;

        if(isset($area) && is_object($area))
            $areaId = $area->area_id;
        else if($this->auth['username'] == 'user79')
            $areaId = 61;
        else
            $areaId = 1;

        $this->view->setVar("area", $areaId);

        $this->view->setVar("memberMeta", array('image' => $this->defaultMemberImage));

        $this->crumbs->add('members', $config->application->baseUri.'members', 'Members');
        if(empty($this->crumbs->crumbs['new']['label']))
            $this->crumbs->add('New', '/', 'Add', false); 

        $form = new MembersForm(new Members(), array('new' => true));
        $this->view->setVar("form", $form);

        // $prefixName = json_encode($this->elements->getPrefixNameData());
        // $this->view->setVar("prefixName", $prefixName);
    }

    /**
     * Edits a Members
     *
     * @param string $member_id
     */
    public function editAction($member_id)
    {
        global $config;
        if (!$this->request->isPost()) {

            $member = Members::findFirstBymember_id($member_id);
            if (!$member) {
                $this->flash->error($this->controllerTranslate["Member was not found"]);

                return $this->dispatcher->forward(array(
                    "controller" => "members",
                    "action" => "index"
                ));
            }

            $this->view->member_id = $member->member_id;

            $this->tag->setDefault("member_id", $member->member_id);
            $this->tag->setDefault("code", $member->code);
            $this->tag->setDefault("type", $member->type);
            $this->tag->setDefault("title", $member->title);
            $this->tag->setDefault("firstname", $member->firstname);
            $this->tag->setDefault("lastname", $member->lastname);
            $this->tag->setDefault("citizenid", $member->citizenid);
            $this->tag->setDefault("bloodgroup", $member->bloodgroup);

            if(!is_null($member->birthdate)){
                $arrBirthdate = explode('-', $member->birthdate);
                $this->tag->setDefault("birthyear", $arrBirthdate[0]);
                $this->tag->setDefault("birthmonth", $arrBirthdate[1]);
                $this->tag->setDefault("birthday", $arrBirthdate[2]);
            }
            $this->tag->setDefault("nationality", $member->nationality);
            $this->tag->setDefault("address", $member->address);
            $this->tag->setDefault("address2", $member->address2);
            // $this->tag->setDefault("geo", $member->geo_id);
            // $this->tag->setDefault("province", $member->province_id);
            // $this->tag->setDefault("district", $member->district_id);
            // $this->tag->setDefault("subdistrict", $member->subdistrict_id);
            $this->tag->setDefault("zipcode", $member->zipcode);
            $this->tag->setDefault("position", $member->position);
            $this->tag->setDefault("standing", $member->standing);
            $this->tag->setDefault("email", $member->email);
            $this->tag->setDefault("area_code", $member->area_code);
            $this->tag->setDefault("telephone", $member->telephone);
            $this->tag->setDefault("mobile", $member->mobile);
            $this->tag->setDefault("fax", $member->fax);
            $this->tag->setDefault("status", $member->status);

            $member->registered = ($member->registered == $this->defaultRegisteredDate) ? date('Y-m-d H:i:s') : $member->registered;
            $this->tag->setDefault("registered", $member->registered);

            $this->view->setVar("memberMeta", array('image' => $this->defaultMemberImage));

            $memberMetaRows = MembersMeta::find("member_id = " . $member->member_id);
            foreach ($memberMetaRows as $memberMeta) {
                $this->tag->setDefault('meta['. $memberMeta->meta_key . ']', $memberMeta->meta_value);

                if($memberMeta->meta_key == 'image')
                    $this->view->setVar("memberMeta", array('image' => $this->uploadLocation . $memberMeta->meta_value));
            }

            if(!empty($member->membersAcademy->academy_id)){
                $academy_id = $member->membersAcademy->academy_id;
                $academy = Academy::findFirstByacademy_id($academy_id);
                $area_id = $academy->area_id;

                $this->view->setVar("academy_id", $academy_id);
                $this->view->setVar("area_id", $area_id);
            }
            

            $this->crumbs->add('members', $config->application->baseUri.'members', 'Members');
            if(empty($this->crumbs->crumbs['Edit']['label']))
                $this->crumbs->add('Edit', '/', 'Edit', false); 

            $referer = $this->request->getHTTPReferer();
            $this->view->setVar("referer", $referer);

            $form = new MembersForm(new Members(), array('edit' => true));
            $this->view->setVar("form", $form);

            $this->view->setVar("member", $member);

            $elementPrefix = $this->elements->getPrefixNameData();
            $this->view->setVar("name", $elementPrefix[$member->title] . ' ' . $member->firstname . ' ' . $member->lastname );

        }
    }

    public function createMembers($memberData)
    {
        $member = new Members();

        $member->code = $memberData['code'];
        $member->type = $memberData['type'];
        $member->title = $memberData['title'];
        $member->firstname = $memberData['firstname'];
        $member->lastname = $memberData['lastname'];
        $member->citizenid = $memberData['citizenid'];
        $member->bloodgroup = $memberData['bloodgroup'];

        if(!empty($memberData['birthyear']) && !empty($memberData['birthmonth']) && !empty($memberData['birthday']))
            $member->birthdate = $memberData['birthyear'] . '-' . $memberData['birthmonth'] . '-' . $memberData['birthday'];

        $member->nationality = $memberData['nationality'];
        $member->address = $memberData['address'];
        $member->address2 = $memberData['address2'];
        $member->geo_id = $memberData['geo'];
        $member->province_id = $memberData['province'];
        $member->district_id = $memberData['district'];
        $member->subdistrict_id = $memberData['subdistrict'];
        $member->zipcode = $memberData['zipcode'];
        $member->position = $memberData['position'];
        $member->standing = $memberData['standing'];
        $member->email = $memberData['email'];
        $member->area_code = $memberData['area_code'];
        $member->telephone = $memberData['telephone'];
        $member->mobile = $memberData['mobile'];
        $member->fax = $memberData['fax'];
        $member->registered = $memberData['registered'];
        $member->status = $memberData['status'];
        $member->lastupdated = date('Y-m-d H:i:s');

        $member->username = str_replace("-", "", $member->citizenid);
        $member->password = str_replace("-", "", $member->citizenid);

        if (!$member->save()) {
            foreach ($member->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "members",
                "action" => "new"
            ));
        }

        return $member->member_id;
    }

    public function createMembersMeta($memberId, $memberMetaData)
    {
        $metaArray = $memberMetaData['meta'];

        if ($this->request->hasFiles() == true) {

            if(isset($_FILES['image'])){
                foreach ($this->request->getUploadedFiles() as $file){
                    //Move the file into the application
                    $file->moveTo($this->uploadLocation . $file->getName());
                    $path = pathinfo($this->uploadLocation . $file->getName());
                    
                    $filename = $this->uploadLocation . $memberId . '.' . $path['extension'];
                    rename($this->uploadLocation . $path['basename'], $filename);
                }
                $metaArray['image'] = $memberId . '.' . $path['extension'];
            }
        }

        foreach ($metaArray as $metaKey => $metaValue) {
            if(!empty($metaValue)){
                $memberMeta = new Membersmeta();
                $memberMeta->member_id = $memberId;
                $memberMeta->meta_key = $metaKey;
                $memberMeta->meta_value = $metaValue;

                if (!$memberMeta->save()) {
                    foreach ($memberMeta->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                        return $this->dispatcher->forward(array(
                        "controller" => "members",
                        "action" => "new"
                    ));
                }
            }
        }

        return true;
    }

    public function createAreaDirector($memberId, $areaId)
    {
        $areaDirector = new AreaDirector();

        $areaDirector->member_id = $memberId;
        $areaDirector->area_id = $areaId;

        if (!$areaDirector->save()) {
            foreach ($memberMeta->getMessages() as $message) {
                $this->flash->error($message);
            }
                return $this->dispatcher->forward(array(
                "controller" => "members",
                "action" => "new"
            ));
        }

        return true;
    }

    public function createMembersAcademy($memberId, $memberData)
    {
        $memberAcademy = new MembersAcademy();

        $memberAcademy->member_id = $memberId;
        $memberAcademy->academy_id = $memberData['academy_id'];

        if (!$memberAcademy->save()) {
            foreach ($memberAcademy->getMessages() as $message) {
                $this->flash->error($message);
            }
                return $this->dispatcher->forward(array(
                "controller" => "members",
                "action" => "new"
            ));
        }

        return true;
    }

    public function viewAction($member_id)
    {
        global $config;

        $member = Members::findFirstBymember_id($member_id);
        // var_dump($member);
        if (!$member) {
            $this->flash->error($this->controllerTranslate["Member was not found"]);

            return $this->dispatcher->forward(array(
                "controller" => "members",
                "action" => "index"
            ));
        }

        $this->view->member_id = $member->member_id;

        //set default meta
        $memberMetaArray['note'] = $memberMetaArray['mate'] = $memberMetaArray['retired'] = $this->defaultNullData;

        $memberMetaArray['image'] = $this->defaultMemberImage;

        foreach ($member->membersMeta as $memberMeta) {
            if($memberMeta->meta_key == 'image')
                $memberMetaArray[$memberMeta->meta_key] =  $this->uploadLocation . $memberMeta->meta_value;
            else
                $memberMetaArray[$memberMeta->meta_key] = $memberMeta->meta_value;
        }

        $this->view->memberMeta = $memberMetaArray;

  
        if(empty($this->crumbs->crumbs['View']['label']))
            $this->crumbs->add('View', '/', 'View', false); 

        $member->province_name = $member->province->province_name;
        $member->district_name = $member->district->district_name;
        $member->subdistrict_name = $member->subdistrict->subdistrict_name;

        if($member->province_id != 1){
            $member->subdistrict_prefix = 'ตำบล';
            $member->district_prefix = 'อำเภอ';
        }
        else{
            $member->subdistrict_prefix = 'แขวง';
            $member->district_prefix = 'เขต';
        }

        if($member->status){
            if(!empty($member->membersAcademy->academy_id)){
                $academy = Academy::findFirstByAcademy_id($member->membersAcademy->academy_id);

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
        $this->view->setVar("name", $elementPrefix[$member->title] . ' ' . $member->firstname . ' ' . $member->lastname );
        $this->view->setVar("member", $member);
    }
    /**
     * Creates a new Members
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "members",
                "action" => "new",
            ));
        }

        if($memberId = $this->createMembers($this->request->getPost())){
            $this->flash->success($this->controllerTranslate["Member was created successfully"]);

            if($this->createMembersMeta($memberId, $this->request->getPost()))
                $this->flash->success($this->controllerTranslate["Member Meta was created successfully"]);

            if($this->createMembersAcademy($memberId, $this->request->getPost()))
                $this->flash->success($this->controllerTranslate["Member Academy was created successfully"]);

            return $this->dispatcher->forward(array(
                "controller" => "members",
                "action" => "index"
            ));
        }

        return $this->dispatcher->forward(array(
            "controller" => "members",
            "action" => "new"
        ));
    }

    /**
     * Saves a Members edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "members",
                "action" => "index"
            ));
        }

        $member_id = $this->request->getPost("member_id");

        $member = Members::findFirstBymember_id($member_id);
        if (!$member) {
            $this->flash->error($this->controllerTranslate["Member does not exist"]);

            return $this->dispatcher->forward(array(
                "controller" => "members",
                "action" => "index"
            ));
        }

        $member->code = $this->request->getPost('code');
        $member->type = $this->request->getPost('type');
        $member->title = $this->request->getPost('title');
        $member->firstname = $this->request->getPost('firstname');
        $member->lastname = $this->request->getPost('lastname');
        $member->citizenid = $this->request->getPost('citizenid');
        $member->bloodgroup = $this->request->getPost('bloodgroup');

        $birthyear = $this->request->getPost('birthyear');
        $birthmonth = $this->request->getPost('birthmonth');
        $birthday = $this->request->getPost('birthday');

        if(!empty($birthyear) && !empty($birthmonth) && !empty($birthday))
            $member->birthdate = $birthyear . '-' . $birthmonth . '-' . $birthday;

        $member->nationality = $this->request->getPost('nationality');
        $member->address = $this->request->getPost('address');
        $member->address2 = $this->request->getPost('address2');
        $member->geo_id = $this->request->getPost('geo');
        $member->province_id = $this->request->getPost('province');
        $member->district_id = $this->request->getPost('district');
        $member->subdistrict_id = $this->request->getPost('subdistrict');
        $member->zipcode = $this->request->getPost('zipcode');
        $member->position = $this->request->getPost('position');
        $member->standing = $this->request->getPost('standing');
        $member->email = $this->request->getPost('email');
        $member->area_code = $this->request->getPost('area_code');
        $member->telephone = $this->request->getPost('telephone');
        $member->mobile = $this->request->getPost('mobile');
        $member->fax = $this->request->getPost('fax');
        $member->registered = $this->request->getPost('registered');
        $member->status = $this->request->getPost('status');
        $member->lastupdated = date('Y-m-d H:i:s');

        $member->username = $member->citizenid;
        $member->password = $member->citizenid;



        if (!$member->save()) {

            foreach ($member->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->view->setVar("memberMeta", array('image' => $this->defaultMemberImage));

            $memberMetaRows = Membersmeta::find("member_id = " . $member->member_id);
            foreach ($memberMetaRows as $memberMeta) {
                $this->tag->setDefault('meta['. $memberMeta->meta_key . ']', $memberMeta->meta_value);

                if($memberMeta->meta_key == 'image')
                    $this->view->setVar("itemMeta", array('image' => $this->uploadLocation . $memberMeta->meta_value));
            }
            
            $this->view->member_id = $member->member_id;
            $form = new MembersForm(new Members(), array('edit' => true));
            $this->view->setVar("form", $form);
            $elementPrefix =$this->elements->getPrefixNameData();
            $this->view->setVar("name", $elementPrefix[$member->title] . ' ' . $member->firstname . ' ' . $member->lastname );

            $academy_id = $member->membersAcademy->academy_id;
            $academy = Academy::findFirstByacademy_id($academy_id);
            $area_id = $academy->area_id;

            $this->view->setVar("member", $member);
            $this->view->setVar("academy_id", $academy_id);
            $this->view->setVar("area_id", $area_id);

            return $this->dispatcher->forward(array(
                "controller" => "members",
                "action" => "edit",
                "params" => array($member->member_id)
            ));
        }

        $this->flash->success($this->controllerTranslate["Member was updated successfully"]);

        //Members Meta
        $metaArray = $this->request->getPost("meta");

        if ($this->request->hasFiles() == true) {

            if(isset($_FILES['image'])){
                foreach ($this->request->getUploadedFiles() as $file){
                    //Move the file into the application
                    $file->moveTo($this->uploadLocation . $file->getName());
                    $path = pathinfo($this->uploadLocation . $file->getName());
                    
                    $filename = $this->uploadLocation . $member_id . '.' . $path['extension'];
                    rename($this->uploadLocation . $path['basename'], $filename);

                }
                $metaArray['image'] = $member_id . '.' . $path['extension'];
            }
        }

        foreach ($metaArray as $metaKey => $metaValue) {
            if(!empty($metaValue)){

                $memberMeta = Membersmeta::findFirst(array(
                    'member_id = :member_id: and meta_key = :meta_key:',
                    'bind' => array(    
                        'member_id' => $member_id,
                        'meta_key' => $metaKey,
                    )
                ));

                if (!$memberMeta) {// Insert new Meta
                    $memberMeta = new Membersmeta();
                    $memberMeta->member_id = $member->member_id;
                    $memberMeta->meta_key = $metaKey;
                    $memberMeta->meta_value = $metaValue;

                    if (!$memberMeta->save()) {
                        foreach ($memberMeta->getMessages() as $message) {
                            $this->flash->error($message);
                        }
                    }
                }
                else{//Update Meta
                    $memberMeta->member_id = $member->member_id;
                    $memberMeta->meta_key = $metaKey;
                    $memberMeta->meta_value = $metaValue;

                    if (!$memberMeta->save()) {
                        foreach ($memberMeta->getMessages() as $message) {
                            $this->flash->error($message);
                        }
                    }
                }
            }
        }

        $memberAcademy = MembersAcademy::findFirstBymember_id($member_id);
        if ($memberAcademy) {
            $memberAcademy->member_id = $member->member_id;
            $memberAcademy->academy_id = $this->request->getPost('academy_id');

            if (!$memberAcademy->save()) {
                foreach ($memberAcademy->getMessages() as $message) {
                    $this->flash->error($message);
                }
            }
        }

        return $this->dispatcher->forward(array(
            "controller" => "members",
            "action" => "index"
        ));
    }

    /**
     * Deletes a Members
     *
     * @param string $member_id
     */
    public function deleteAction($member_id)
    {

        $Members = Members::findFirstBymember_id($member_id);
        if (!$Members) {
            $this->flash->error($this->controllerTranslate["Member was not found"]);

            return $this->dispatcher->forward(array(
                "controller" => "Members",
                "action" => "index"
            ));
        }

        if (!$Members->delete()) {

            foreach ($Members->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "Members",
                "action" => "search"
            ));
        }

        $this->flash->success($this->controllerTranslate["Member was deleted successfully"]);

        return $this->dispatcher->forward(array(
            "controller" => "Members",
            "action" => "index"
        ));
    }

    public function getMembersAction()
    {
        global $config;

        $this->view->disable();

        if ($this->request->isPost() == true) {

            // Check whether the request was made with Ajax
            if ($this->request->isAjax() == true) {
                $this->response->setContentType('application/json', 'UTF-8');

                $copyId = $this->request->getPost("member_id");
                // $memberId = $this->request->getPost("member_id");

                $memberCopy = MembersCopy::findFirstBycopy_id($copyId);
                if (!$memberCopy) {
                    $data = array(
                        'status' => 'unsuccess',
                        'msg' => 'Members Copy not found'
                    );

                    $response->setContent(json_encode($data));
                    return $response;
                }

                $member = Members::findFirstBymember_id($memberCopy->member_id);
                if (!$member) {
                    $data = array(
                        'status' => 'unsuccess',
                        'msg' => 'Members not found'
                    );

                    $response->setContent(json_encode($data));
                    return $response;
                }

                $memberStatus = ($member->status) ? '<span class="label label-satgreen">' . $this->mainTranslate["Active"] . '</span>' : '<span class="label label-lightred">' . $this->mainTranslate["Inactive"] . '</span>';

                $conditions = "member_id = :member_id: AND meta_key = :meta_key:";
                $parameters = array(
                    "member_id" => $member->member_id,
                    "meta_key" => "image"
                );

                $memberMeta = Membersmeta::findFirst(array(
                    $conditions,
                    "bind" => $parameters
                ));

                if($memberMeta)
                    $memberImage = $this->tag->image(array($this->uploadLocation.$memberMeta->meta_value)); #$this->uploadLocation.$memberMeta->meta_value;
                else
                    $memberImage = $this->tag->image(array($this->defaultMemberImage));

                $response = new \Phalcon\Http\Response();

                $data = array(
                    'status' => 'success',
                    'itemCopyId' => $memberCopy->copy_id, 
                    'itemCopyNo' => $memberCopy->copy, 
                    'itemId' => $member->member_id, 
                    'itemName' => $member->name,
                    'itemAuthor' => $member->author,
                    'itemPublisher' => $member->publisher,
                    'itemIdentity' => $member->identity,
                    'itemAuthorCode' => $member->author_code,
                    'itemStatus' => $memberStatus,
                    'itemImage' => $memberImage,
                );
                $response->setContent(json_encode($data));

                return $response;
            }
        }
    }

    public function getMemberByCitizenAction()
    {
        global $config;

        $this->view->disable();

        if ($this->request->isPost() == true) {

            // Check whether the request was made with Ajax
            if ($this->request->isAjax() == true) {
                $this->response->setContentType('application/json', 'UTF-8');

                $citizenid = $this->request->getPost("citizenid");
                $member = Members::findFirstBycitizenid($citizenid);

                if (!$member) {
                    $data = array(
                        'status' => 'unsuccess',
                        'msg' => 'Members not found'
                    );

                    $response->setContent(json_encode($data));
                    return $response;
                }

                $conditions = "member_id = :member_id: AND meta_key = :meta_key:";
                $parameters = array(
                    "member_id" => $member->member_id,
                    "meta_key" => "image"
                );

                $memberMeta = Membersmeta::findFirst(array(
                    $conditions,
                    "bind" => $parameters
                ));

                if($memberMeta)
                    $memberImage = $this->tag->image(array("src" => $this->uploadLocation.$memberMeta->meta_value, 'class' => "img-responsive")); #$this->uploadLocation.$memberMeta->meta_value;
                else
                    $memberImage = $this->tag->image(array($this->defaultMemberImage));

                $membersMeta = MembersMeta::find("member_id = " . $member->member_id );
                unset($memberMetaArray);
                foreach ($membersMeta as $memberMeta) {
                    $memberMetaArray[$memberMeta->meta_key] = $memberMeta->meta_value;
                }

                $elementPrefix = $this->elements->getPrefixNameData();
                $memberTitle = $elementPrefix[$member->title];

                $elementPrefix =$this->elements->getPrefixNameEnData();
                $memberTitleEn = $elementPrefix[$member->title];

                $memberName = $memberTitle . ' ' . $member->firstname . ' ' . $member->lastname;

                $memberNameEN = $memberTitleEn . ' ' . ucwords(strtolower($memberMetaArray['firstname_en'])) . ' ' . ucwords(strtolower($memberMetaArray['lastname_en']));


                $elementPeopleType = $this->elements->getMembersType();
                $memberType = $elementPeopleType[$member->type];
                $memberType = str_replace(' (ผู้อำนวยการ)', '', $memberType);
                $memberType = str_replace(' (รองผู้อำนวยการ)', '', $memberType);

                $memberCode = (empty($member->code)) ? $defaultNullData : $member->code;
                $memberCode = str_replace(' ', '', $memberCode);
                $memberCode = str_replace('ว.', 'ว', $memberCode);
                $memberCode = str_replace('ว', 'ว ', $memberCode);

                if($member->type == 2)
                    if(!strstr($memberCode, 'ว '))
                        $memberCode = 'ว ' . $memberCode;

                

                $response = new \Phalcon\Http\Response();

                $data = array(
                    'status' => 'success',
                    'memberId' => $member->member_id, 
                    'memberType' => $memberType, 
                    'memberName' => $memberName,
                    'memberNameEN' => $memberNameEN,
                    'memberCitizenId' => $member->citizenid,
                    'memberCode' => $memberCode,
                    'memberBloodGroup' => $member->bloodgroup,
                    'memberImage' => $memberImage,
                );
                $response->setContent(json_encode($data));

                return $response;
            }
        }
    }

    public function grabMemberAction($grabby)
    {
        global $config;

        $this->view->disable();

        if ($this->request->isPost() == true) {

            // Check whether the request was made with Ajax
            if ($this->request->isAjax() == true) {
                $this->response->setContentType('application/json', 'UTF-8');

                switch($grabby){

                    case "citizenid" :
                        $citizenid = $this->request->getPost("citizenid");
                        $member = Members::findFirstBycitizenid($citizenid);
                        break;

                    case "rfid" :
                        $card_id = $this->request->getPost("card_id");
                        $member = $this->getMemberByRFID($card_id);

                        break;
                }

                if (!$member) {
                    $data = array(
                        'status' => 'unsuccess',
                        'msg' => 'Members not found'
                    );

                    $response->setContent(json_encode($data));
                    return $response;
                }

                $conditions = "member_id = :member_id: AND meta_key = :meta_key:";
                $parameters = array(
                    "member_id" => $member->member_id,
                    "meta_key" => "image"
                );

                $memberMeta = Membersmeta::findFirst(array(
                    $conditions,
                    "bind" => $parameters
                ));

                if($memberMeta)
                    $memberImage = $this->tag->image(array("src" => $this->uploadLocation.$memberMeta->meta_value, 'class' => "img-responsive")); #$this->uploadLocation.$memberMeta->meta_value;
                else
                    $memberImage = $this->tag->image(array($this->defaultMemberImage));

                $membersMeta = MembersMeta::find("member_id = " . $member->member_id );
                unset($memberMetaArray);
                foreach ($membersMeta as $memberMeta) {
                    $memberMetaArray[$memberMeta->meta_key] = $memberMeta->meta_value;
                }

                $elementPrefix = $this->elements->getPrefixNameData();
                $memberTitle = $elementPrefix[$member->title];

                $elementPrefix =$this->elements->getPrefixNameEnData();
                $memberTitleEn = $elementPrefix[$member->title];

                $memberName = $memberTitle . ' ' . $member->firstname . ' ' . $member->lastname;

                $memberNameEN = $memberTitleEn . ' ' . ucwords(strtolower($memberMetaArray['firstname_en'])) . ' ' . ucwords(strtolower($memberMetaArray['lastname_en']));


                $elementPeopleType = $this->elements->getMembersType();
                $memberType = $elementPeopleType[$member->type];
                $memberType = str_replace(' (ผู้อำนวยการ)', '', $memberType);
                $memberType = str_replace(' (รองผู้อำนวยการ)', '', $memberType);

                $memberCode = (empty($member->code)) ? $defaultNullData : $member->code;
                $memberCode = str_replace(' ', '', $memberCode);
                $memberCode = str_replace('ว.', 'ว', $memberCode);
                $memberCode = str_replace('ว', 'ว ', $memberCode);

                if($member->type == 2)
                    if(!strstr($memberCode, 'ว '))
                        $memberCode = 'ว ' . $memberCode;


                //Academy
                $memberAcademy = MembersAcademy::findFirstByMember_id($member->member_id);
                $academy = Academy::findFirstByacademy_id($memberAcademy->academy_id);

                $academy->province_name = $academy->province->province_name;
                $academy->district_name = $academy->district->district_name;
                $academy->subdistrict_name = $academy->subdistrict->subdistrict_name;

                $memberAcademyProvince = $academy->province_name;



                //Geo
                $memberGeo = Geography::findFirstByGeo_id($academy->province->geo_id);
                $memberGeoName = $memberGeo->geo_name;

                $response = new \Phalcon\Http\Response();

                $data = array(
                    'status' => 'success',
                    'memberId' => $member->member_id, 
                    'memberType' => $memberType, 
                    'memberName' => $memberName,
                    'memberNameEN' => $memberNameEN,
                    'memberCitizenId' => $member->citizenid,
                    'memberCode' => $memberCode,
                    'memberBloodGroup' => $member->bloodgroup,
                    'memberImage' => $memberImage,
                    'memberAcademyProvince' => $memberAcademyProvince,
                    'memberAcademy' => $academy->name,
                    'memberGeo' => $memberGeoName,
                );
                $response->setContent(json_encode($data));

                return $response;
            }
        }

    }

    public function completeAction()
    {

        if(isset($this->auth['province']) && !empty($this->auth['province']) || $this->auth['role'] == 'A'){

            // $this->view->setLayout('blank');
            if($this->auth['role'] == 'A'){
                $queryMemberByAcademyProvince = "SELECT m.* FROM Phoenix\Models\Members AS m LEFT JOIN Phoenix\Models\MembersAcademy AS ma ON m.member_id = ma.member_id LEFT JOIN Phoenix\Models\Academy AS a ON a.academy_id = ma.academy_id WHERE m. ORDER BY m.member_id DESC";
                $provinceName = '';
            }
            else{
                $provinceId = $this->auth['province'];
                $province = Province::findFirstByprovince_id($provinceId);
                $provinceName = $province->province_name;

                $queryMemberByAcademyProvince = "SELECT m.* FROM Phoenix\Models\Members AS m LEFT JOIN Phoenix\Models\MembersAcademy AS ma ON m.member_id = ma.member_id LEFT JOIN Phoenix\Models\Academy AS a ON a.academy_id = ma.academy_id WHERE a.province_id = " . $provinceId . " ORDER BY m.member_id DESC";
            }

            $member = $this->modelsManager->executeQuery($queryMemberByAcademyProvince);
            $memberTotal = count($member);
        }
        else
            $provinceName = '';

        if ($this->request->isPost()) {

            $memberLog = new MembersLog();

            $memberLog->province_id = $provinceId;
            $memberLog->province = $provinceName;
            $memberLog->username = $this->auth['username'];
            $memberLog->name = $this->auth['name'];
            $memberLog->members = $memberTotal;
            $memberLog->completed = date('Y-m-d H:i:s');

            if (!$memberLog->save()) {
                foreach ($memberLog->getMessages() as $message) {
                    $this->flash->error($message);
                }
            }

            $this->view->setLayout('blank');

            if(!$this->mail->send(
                array('pangpond@netop.co.th' => 'pangpond', 'dev@netop.co.th' => 'Dev Netop', 'ploypreeya@netop.co.th' => 'Ploy Netop', 'kanseet@netop.co.th' => 'Tho Netop', 'yotchanan@netop.co.th' => 'Yotchanan Netop'),
                '[members.saat.or.th] แจ้งกรอกข้อมูลสมาชิก จังหวัด' . $provinceName . 'ครบถ้วน',
                'info',
                array(
                    'title' => 'แจ้งกรอกข้อมูลครบถ้วน จังหวัด <strong>' . $provinceName . '</strong>',
                    'msg' => 'จาก <strong>' . $this->auth['username'] . '</strong> (' . $this->auth['name'] . ')',
                    'provinceName' => $provinceName,
                     'memberTotal' => $memberTotal 
                )
            )){
                $this->flash->error($this->controllerTranslate["Member completed data notice unsuccessfully send"]);
                return $this->dispatcher->forward(array(
                    "controller" => "members",
                    "action" => "index"
                ));
            }

            $this->view->setTemplateAfter('flat');
            $this->view->setLayout('main');
            
            $this->flash->success($this->controllerTranslate["Member completed data notice successfully send"]);

            return $this->view->render("members/index", array());
        }

        $numberPage = $this->request->getQuery("page", "int");
        if(empty($numberPage))
            $numberPage = 1;

        if (count($member) == 0) {
            $this->flash->notice($this->controllerTranslate["The search did not find any member"]);
        }

        $paginator = new Paginator(array(
            "data" => $member,
            "limit"=> 100,
            "page" => $numberPage
        ));

        $form = new MembersForm(new Members(), array('search' => true));
        $this->view->setVar("form", $form);

        $page = $paginator->getPaginate();
        $this->view->setVar('page', $page);
        
        $this->view->setVar('searchType', 'index');
        $this->view->setVar('defaultNullData', $this->defaultNullData);
        $this->view->setVar('title', 'Member List');
        $this->view->setVar('defaultMemberImage', $this->defaultMemberImage);
        $this->view->setVar('uploadLocation', $this->uploadLocation);
        $this->view->setVar("provinceName", $provinceName);
        $this->view->setVar("memberTotal", $memberTotal);
        $this->crumbs->add('Completed', '/', 'Completed Data', false); 
    }

    public function qualifyAction($filter = 'all')
    {

        $member = $this->getMembersCardBacklog();
        $memberTotal = count($member);

        $provinceName = '';

        $numberPage = $this->request->getQuery("page", "int");
        if(empty($numberPage))
            $numberPage = 1;

        if (count($member) == 0) {
            $this->flash->notice($this->controllerTranslate["The search did not find any member"]);
        }

        $paginator = new Paginator(array(
            "data" => $member,
            "limit"=> 10000,
            "page" => $numberPage
        ));

        $form = new MembersForm(new Members(), array('search' => true));
        $this->view->setVar("form", $form);

        $page = $paginator->getPaginate();
        $this->view->setVar('page', $page);

        if($filter == 'passed')
            $filterTitle = 'ทำบัตรได้ ';
        else if($filter == 'notpass')
            $filterTitle = 'ทำบัตรไม่ได้ ';
        else
            $filterTitle = '';
        
        $this->view->setVar('searchType', 'index');
        $this->view->setVar('defaultNullData', $this->defaultNullData);
        $this->view->setVar('title', 'Member List');
        $this->view->setVar('defaultMemberImage', $this->defaultMemberImage);
        $this->view->setVar('uploadLocation', $this->uploadLocation);
        $this->view->setVar("provinceName", $provinceName);
        $this->view->setVar("memberTotal", $memberTotal);
        $this->view->setVar("filter", $filter);
        $this->view->setVar("filterTitle", $filterTitle);

        $this->crumbs->add('Completed', '/', 'Membership Qualify Data', false); 
    }

    public function unQualifyAction($filter = 'all')
    {

        $member = $this->getMembersInCompleteInfo();
        $memberTotal = count($member);

        $provinceName = '';

        $numberPage = $this->request->getQuery("page", "int");
        if(empty($numberPage))
            $numberPage = 1;

        if (count($member) == 0) {
            $this->flash->notice($this->controllerTranslate["The search did not find any member"]);
        }

        $paginator = new Paginator(array(
            "data" => $member,
            "limit"=> 100,
            "page" => $numberPage
        ));

        $page = $paginator->getPaginate();
        $this->view->setVar('page', $page);
        
        $this->view->setVar('searchType', 'index');
        $this->view->setVar('defaultNullData', $this->defaultNullData);
        $this->view->setVar('title', 'Member List');
        $this->view->setVar('defaultMemberImage', $this->defaultMemberImage);
        $this->view->setVar('uploadLocation', $this->uploadLocation);
        $this->view->setVar("provinceName", $provinceName);
        $this->view->setVar("memberTotal", $memberTotal);
        $this->view->setVar("filter", $filter);

        $this->crumbs->add('Completed', '/', 'Membership Unqualify Data', false); 
    }

    public function getMembersCompleteInfo()
    {

        $queryMembers = "SELECT * FROM Phoenix\Models\MembersCompleteInfo ORDER BY member_id ASC";

        $members = $this->modelsManager->executeQuery($queryMembers);

        if (count($members) == 0)
            return false;
        else
            return $members;
    }

    public function getMembersCardBacklog()
    {

        $queryMembers = "SELECT * FROM Phoenix\Models\MembersCardBacklog ORDER BY member_id ASC";

        $members = $this->modelsManager->executeQuery($queryMembers);

        if (count($members) == 0)
            return false;
        else
            return $members;
    }

    public function getMembersInCompleteInfo()
    {

        $queryMembers = "SELECT m.* FROM Phoenix\Models\Members m LEFT JOIN Phoenix\Models\MembersCompleteInfo mc ON m.member_id = mc.member_id WHERE mc.member_id is null";

        $members = $this->modelsManager->executeQuery($queryMembers);

        if (count($members) == 0)
            return false;
        else
            return $members;
    }

    public function getMemberByRFID($rfid)
    {

        // $queryMember = "SELECT * FROM Phoenix\Models\Members m LEFT JOIN Phoenix\Models\MembersCard mc ON m.member_id = mc.member_id WHERE mc.card_id = " . $rfid . "limit 1";

        // $member = $this->modelsManager->executeQuery($queryMember);
        // 
        // 
        // / $member = $this->modelsManager->createBuilder()
        //     ->addFrom('Phoenix\Models\Members', 'm')
        //     ->leftJoin('Phoenix\Models\MembersCard', 'm.member_id = mc.member_id', 'mc')
        //     ->where('mc.card_id = :card_id:', array('card_id' => '0009148871'))
        //     ->getQuery()
        //     ->execute();


        $memberCard = MembersCard::findFirstBycard_id($rfid);
        if (count($memberCard) == 0)
            return false;

        $member = Members::findFirstBymember_id($memberCard->member_id);

        if (count($member) == 0)
            return false;
        else
            return $member;
    }

    public function exportAction($kind, $filter)
    {

        $this->view->disable();

        /** PHPExcel */
        include '../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
        /** PHPExcel_Writer_Excel2007 */
        include '../vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel5.php';

        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getActiveSheet()->setTitle('MembershipCard ' . date('Y-m-d'));

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Name')
            ->setCellValue('C1', 'Name Eng')
            ->setCellValue('D1', 'Citizen ID')
            ->setCellValue('E1', 'Code')
            ->setCellValue('F1', 'Type')
            ->setCellValue('G1', 'Bloodgroup')
            ->setCellValue('H1', 'Image');

        switch ($kind) {
            case 'qualify':
                $members = $this->getMembersCardBacklog();
                break;

            case 'unqualify':
                $members = $this->getMembersInCompleteInfo();
                break;

            default:
                $members = $this->getMembersCardBacklog();
                break;
        }
        


        $rowNotPassedMember = $rowPassedMember = 1;
        foreach ($members as $member) { 

            $elementPeopleType = $this->elements->getMembersType();
            $memberType = $elementPeopleType[$member->type];
            $memberType = str_replace(' (ผู้อำนวยการ)', '', $memberType);
            $memberType = str_replace(' (รองผู้อำนวยการ)', '', $memberType);


            $elementPrefix =$this->elements->getPrefixNameData();
            $memberTitle = $elementPrefix[$member->title];

            $elementPrefix =$this->elements->getPrefixNameEnData();
            $memberTitleEn = $elementPrefix[$member->title];

            $registered = $this->datetime->date(strtotime($member->registered));

            $academyName = $this->defaultNullData;
            $areaName = $this->defaultNullData;
            if(!empty($member->membersAcademy->academy_id)){
                    $academy = Academy::findFirstByAcademy_id($member->membersAcademy->academy_id);
                if($academy){
                    $academyName = $academy->name;
                    $areaName = $academy->area->name;
                }
            }

            $areaNameSearch = strpos($areaName, "เขต");
            if ($areaNameSearch == true) {
                $areaNumber = str_replace("สำนักงานเขตพื้นที่การศึกษามัธยมศึกษาเขต", "", $areaName);
            }else{
                $areaNumber = str_replace("องค์กรปกครองส่วนท้องถิ่น ", "", $areaName);
            }

            $province = $academy->province->province_name;

            $memberCode = (empty($member->code)) ? $this->defaultNullData : $member->code;

            $memberCode = str_replace(' ', '', $memberCode);
            $memberCode = str_replace('ว.', 'ว', $memberCode);
            $memberCode = str_replace('ว', 'ว ', $memberCode);

            if($member->type == 2)
                if(!strstr($memberCode, 'ว '))
                    $memberCode = 'ว ' . $memberCode;

            $membersMeta = MembersMeta::find("member_id = " . $member->member_id );

            $memberMetaImage = $this->defaultMemberImage;
            $memberImageSize[0] = $memberImageSize[1] = 0;
            unset($memberMetaArray);
            foreach ($membersMeta as $memberMeta) {
                if($memberMeta->meta_key == 'image'){
                    $memberMetaImage = $this->uploadLocation . $memberMeta->meta_value;
                    if(file_exists($memberMetaImage))
                        $memberImageSize = getimagesize($memberMetaImage);
                }
                else{
                    $memberMetaArray[$memberMeta->meta_key] = $memberMeta->meta_value;
                }
            }

            if( !isset($memberMetaArray['firstname_en']) )
                $memberMetaArray['firstname_en'] = '';

            if( !isset($memberMetaArray['lastname_en']) )
                $memberMetaArray['lastname_en'] = '';


            if( $memberImageSize[0] >= 150 /*&& $memberImageSize[0] < 300*/ && $memberImageSize[1] != 0 && $memberCode != 'N/A'){
                $validateStatus = '<span class="label label-satgreen">Pass</span>';
                $validate = 'passed';
            }
            else{
                $validateStatus = '<span class="label label-lightred">Not Pass</span>';
                $validate = 'notpass';
            }

            if($filter == 'passed'){
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . ($rowPassedMember + 1), ($rowPassedMember))
                ->setCellValue('B' . ($rowPassedMember + 1), $memberTitle . ' ' . $member->firstname . ' ' . $member->lastname)
                ->setCellValue('C' . ($rowPassedMember + 1), $memberTitleEn . ' ' . ucwords(strtolower($memberMetaArray['firstname_en'])) . ' ' . ucwords(strtolower($memberMetaArray['lastname_en'])))
                ->setCellValue('D' . ($rowPassedMember + 1), $member->citizenid)
                ->setCellValue('E' . ($rowPassedMember + 1), $memberCode)
                ->setCellValue('F' . ($rowPassedMember + 1), $memberType)
                ->setCellValue('G' . ($rowPassedMember + 1), $member->bloodgroup)
                ->setCellValue('H' . ($rowPassedMember + 1), $memberMetaImage);

                if($validate ==  'notpass'){
                    $rowNotPassedMember++;
                    continue;
                }
            }

            if($filter == 'notpass'){
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . ($rowPassedMember + 1), ($rowPassedMember))
                ->setCellValue('B' . ($rowPassedMember + 1), $memberTitle . ' ' . $member->firstname . ' ' . $member->lastname)
                ->setCellValue('C' . ($rowPassedMember + 1), $memberTitleEn . ' ' . ucwords(strtolower($memberMetaArray['firstname_en'])) . ' ' . ucwords(strtolower($memberMetaArray['lastname_en'])))
                ->setCellValue('D' . ($rowPassedMember + 1), $member->citizenid)
                ->setCellValue('E' . ($rowPassedMember + 1), $memberCode)
                ->setCellValue('F' . ($rowPassedMember + 1), $memberType)
                ->setCellValue('G' . ($rowPassedMember + 1), $member->bloodgroup)
                ->setCellValue('H' . ($rowPassedMember + 1), $memberMetaImage);

                if($validate ==  'pass')
                    continue;
            }

            $rowPassedMember++;

        }//end for

        // file name to output
        $fname = date("Ymd_his") . ".xls";

        // temp file name to save before output
        $temp_file = tempnam(sys_get_temp_dir(), 'phpexcel');

        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
        $objWriter->save($temp_file);

        $response = new \Phalcon\Http\Response();

        // Redirect output to a client’s web browser (Excel2005)
        $response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->setHeader('Content-Disposition', 'attachment;filename="' . $fname . '"');
        $response->setHeader('Cache-Control', 'max-age=0');

        // If you're serving to IE 9, then the following may be needed
        $response->setHeader('Cache-Control', 'max-age=1');

        //Set the content of the response
        $response->setContent(file_get_contents($temp_file));

        // delete temp file
        unlink($temp_file);

        //Return the response
        return $response;
    }

    public function testAction()
    {

        $this->view->disable();
        $members = $this->getMembersCompleteInfoNonPrinted();

        count($members);
    }

    public function tcpdfAction()
    {
        global $config;

        $this->view->disable();

        $this->tcpdf->setPrintHeader(false);
        $this->tcpdf->setPrintFooter(false);
        $this->tcpdf->SetMargins(PDF_MARGIN_LEFT, $PDF_MARGIN_TOP = 4, PDF_MARGIN_RIGHT);
        $this->tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $this->tcpdf->SetAutoPageBreak(TRUE, $PDF_MARGIN_BOTTOM = 80);
        // $this->tcpdf->addTTFfont('fonts/THSarabun.ttf', '', '', 12);

        $this->tcpdf->SetFont('thsarabunpsk', '', 12);
        // $this->tcpdf->SetFont('thsarabunpskb', '', 12);
        $pageSetup = 'P';
        $this->tcpdf->AddPage($pageSetup, 'A4');

        //define html template path
        define('REPORT_TEMPLATE_PATH', $config->application->viewsDir . 'reportTemplates/');
        define('REPORT_PDF_PATH', 'report/');

        // read content from html template file
        $reportContent = file_get_contents(REPORT_TEMPLATE_PATH . $reportFile = 'events-participate.html', FILE_USE_INCLUDE_PATH);


        $reportContent = str_replace('{{STYLE}}', '<style>'.file_get_contents(__DIR__ . '/../views/reportTemplates/stylesheets/pdf.css').'</style>', $reportContent);

        // echo $reportContent;exit;
        $this->tcpdf->writeHTML($reportContent, true, false, true, false, 'left');

        // reset pointer to the last page
        $this->tcpdf->lastPage();

        //Close and output PDF document
        $this->tcpdf->Output('event.pdf', 'I');
    }

    public function dompdfAction()
    {
        global $config;

        $this->view->disable();

        //define html template path
        define('REPORT_TEMPLATE_PATH', $config->application->viewsDir . 'reportTemplates/');
        define('REPORT_PDF_PATH', 'report/event');

        // read content from html template file
        $reportContent = file_get_contents(REPORT_TEMPLATE_PATH . $reportFile = 'events-participate.html', FILE_USE_INCLUDE_PATH);

        // echo $reportContent; exit;

        // generate PDF
        $this->dompdf->set_base_path( REPORT_TEMPLATE_PATH );
        $this->dompdf->load_html($reportContent);
        $this->dompdf->set_paper('a4'); 

        $this->dompdf->render();
        $this->dompdf->stream($reportCode = "event" . ".pdf", array('Attachment'=>0, 'compress'=>1));
    }

    public function eventParticipateAction($eventId)
    {
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
                foreach ($participate as $member) {
                    $elementPrefix =$this->elements->getPrefixNameData();
                    $memberTitle = $elementPrefix[$member->title];

                    $row .= '<tr>
                                <td class="text-center xl" >' . $rowNumber . '</td>
                                <td class="xl">' . $memberTitle . ' ' . $member->firstname .' ' . $member->lastname . '</td>
                            </tr>';
                    $rowNumber++;
                }

                $reportContentArray[$member->province_id] = str_replace('{{ROW}}', $row, $reportContentArray[$member->province_id]);
                $reportContentArray[$member->province_id] = str_replace('{{PROVINCE}}', $member->province_name, $reportContentArray[$member->province_id]);

                $reportContent .= $reportContentArray[$member->province_id];
            }

            $reportTemplate = str_replace('{{BODY}}', $reportContent, $reportTemplate);

            // generate PDF
            $this->dompdf->load_html($reportTemplate);
            $this->dompdf->render();
            $this->dompdf->stream($reportCode = "event" . ".pdf", array('Attachment'=>0, 'compress'=>1));
        }

    }

    public function mappingAction()
    {

        if ($this->request->isPost()) {

            $memberCard = new MembersCard();

            $memberCard->card_id = $this->request->getPost('mapping_card_id');
            $memberCard->member_id = $this->request->getPost('mapping_member_id');
            $memberCard->citizenid = $this->request->getPost('mapping_citizenid');
            $memberCard->lot_id = $this->request->getPost('mapping_lot_id');

            if (!$memberCard->save()) {
                foreach ($memberCard->getMessages() as $message) {
                    $this->flash->error($message);
                }

                return $this->dispatcher->forward(array(
                    "controller" => "members",
                    "action" => "mapping"
                ));
            }

            $this->flash->success($this->controllerTranslate["Mapping Membership card was successfully"] . ' (' . $this->request->getPost('mapping_citizenid') . ')' );
        }


        $this->tag->setDefault("citizenid", '');
        $this->tag->setDefault("card_id", '');
        $this->tag->setDefault("lot_id", '1');
        $this->crumbs->add('Mapping', '/', 'Mapping', false); 
    }

    public function scanAction()
    {
        $this->tag->setDefault("citizenid", '');
        $this->tag->setDefault("rfid", '');
        $this->crumbs->add('Scan', '/', 'Scan', false); 
    }


}