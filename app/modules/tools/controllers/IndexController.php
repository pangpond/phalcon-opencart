<?php
namespace Modules\Tools\Controllers;

use Phalcon\Tag,
    // Modules\Tools\Controllers,
    Phalcon\Session as Session;

use Phoenix\Models\Members;

use Phoenix\Controllers\ControllerBase;

use Phoenix\Library\Security\Security;

class IndexController extends ControllerBase
{
    protected $baseSchemasLocation = 'schemas/';

    public function initialize()
    {
        Tag::setTitle('Tools Index');
        $this->loadCustomTrans('tools/index');
        parent::initialize();
        $this->crumbs->add('tools', '', 'Tools'); 
    }

    public function clearDataAction(){

        if( $this->db->query('TRUNCATE table members;') &&
            $this->db->query('TRUNCATE table membersmeta;')  &&
            $this->db->query('TRUNCATE table items;')  &&
            $this->db->query('TRUNCATE table itemsmeta;')  &&
            $this->db->query('TRUNCATE table items_copy;')  &&
            $this->db->query('TRUNCATE table visited;') && 
            $this->db->query('TRUNCATE table rent;') && 
            $this->db->query("INSERT INTO `members` (`member_id`, `username`, `password`, `display_name`, `member_type`, `member_code`, `title`, `firstname`, `lastname`, `status`, `registered`) VALUES (1, 'admin', 'c0bd96dc7ea4ec56741a4e07f6ce98012814d853', 'Administrator', 'A', 'admin', '2', 'admin', 'admin', 1, '2014-09-01 14:33:35');")
        )
            $this->flash->notice($this->controllerTranslate["All data is gone!!"]);

        $this->crumbs->add('tools/index', '/', 'Index', false); 
        $this->view->pick(array("index/index"));
    }

    public function importAction(){

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "index",
                "action" => "index"
            ));
        }

        $importType = $this->request->getPost("type");

        switch ($importType) {
            case 'sample':
                //read schemas file
                //excute sql
                break;
            case 'template':
                    $flagImportTemplate = $this->importTemplate();
                break;
            case 'database':
                # code...
                break;
            default:
                # code...
                break;
        }

        if($flagImportTemplate){
            $this->flash->seccess($this->controllerTranslate["Success"]);
        }else{
            $this->flash->error($this->controllerTranslate["Please choose the file"]);
        }

        $this->crumbs->add('tools/import', '/', 'Import', false); 
        $this->view->pick(array("index/index"));
    }

    public function indexAction()
    {

        $this->crumbs->add('tools/index', '/', 'Index', false); 

     }

     public function templateAction()
    {

        $this->crumbs->add('tools/template', '/', 'Template', false); 

     }

     public function exportAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "tools",
                "action" => "index"
            ));
        }

        $type = $this->request->getPost("type");


        if($type == 'backup')
            $this->Mysqldump->start('tools/backup/Backup-'. date('Y-m-d-His') .'.sql');

        $this->flash->notice($this->controllerTranslate["Backup date completed"]);

        $this->crumbs->add('tools/index', '/', 'Index', false); 
        $this->view->pick(array("index/index"));

     }

     public function excelAction()
     {

        $inputFileName = 'uploads/data/SuperLibraryTemplate.xls';

        $dataMembers = $this->readExcel($inputFileName, 0, 4);
        foreach ($dataMembers as $dataMember) {
            if($this->import->importMembers($dataMember))
                $this->flash->success("Member was imported successfully");
        }

        $dataItems = $this->readExcel($inputFileName, 1, 4);
        foreach ($dataItems as $dataItem) {
            if($this->import->importItems($dataItem))
                $this->flash->success("Item was imported successfully");
        }
        
        $this->view->pick(array("index/index"));
     }

     public function readExcel($inputFileName, $sheetIndex, $headerRow){

        $objReader = $this->PHPExcel;
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($inputFileName);

        $objWorksheet = $objPHPExcel->setActiveSheetIndex($sheetIndex);
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();

        $headingsArray = $objWorksheet->rangeToArray('A'.$headerRow.':'.$highestColumn.$headerRow,null, true, true, true);
        $headingsArray = $headingsArray[$headerRow];

        $r = -1;
        $importDataArray = array();
        for ($row = $headerRow + 1; $row <= $highestRow; ++$row) {
            $dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
            if ((isset($dataRow[$row]['A'])) && ($dataRow[$row]['A'] > '')) {
                ++$r;
                foreach($headingsArray as $columnKey => $columnHeading) {
                    $importDataArray[$r][$columnHeading] = $dataRow[$row][$columnKey];
                }
            }
        }

        return $importDataArray;

     }

     public function importTemplate(){


        $inputFileName = $_FILES['file']['tmp_name']; 
        if(empty($inputFileName)){
            return false;
        }

        $dataMembers = $this->readExcel($inputFileName, 0, 4);
        foreach ($dataMembers as $dataMember) {
            if($this->import->importMembers($dataMember))
                $this->flash->success("Member was imported successfully");
        }

        $dataItems = $this->readExcel($inputFileName, 1, 4);
        foreach ($dataItems as $dataItem) {
            if($this->import->importItems($dataItem))
                $this->flash->success("Item was imported successfully");
        }
        return true;
        // var_dump($dataMember);
        // var_dump($dataItem);
     }

}