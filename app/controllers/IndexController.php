<?php
namespace Phoenix\Controllers;

use Phalcon\Mvc\Model\Criteria,
    Phalcon\Tag;

use Phoenix\Models\Users;
use Phoenix\Models\Settings;

class IndexController extends ControllerBase
{

    protected $itemBookType = array(1, 2, 3, 4, 5, 6, 7);

    public function initialize()
    {
        Tag::setTitle('Dashboard');
        $this->loadCustomTrans('index');
        parent::initialize();

        $language = $this->session->get("language");
        if (!$language) {
            $this->session->set("language", "en");
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

        $this->crumbs->add('index', $this->applicationConfig->baseUri.'index', 'Index');
    }

    public function indexAction()
    {
        $this->view->setVar('title', 'Dashboard');
        $this->crumbs->add('index', '', 'Dashboard', false);

        $auth = $this->session->get('auth');

    }

    public function newAction()
    {
        $language = $this->session->get('language');
        //Get session info
        $auth = $this->session->get('auth');

    }

    public function setLanguageAction($language='')
    {
        //Change the language, reload translations if needed
        if ($language == 'en' || $language == 'th' || $language == 'cn') {
            $this->session->set('language', $language);
            $this->loadMainTrans();
            $this->loadCustomTrans('index');
        }

        //Go to the last place
        $referer = $this->request->getHTTPReferer();
        if (strpos($referer, $this->request->getHttpHost()."/")!==false) {
            return $this->response->setHeader("Location", $referer);
        } else {
            return $this->dispatcher->forward(array('controller' => 'index', 'action' => 'index'));
        }
    }

    function getItemsCategoryData(){
        
        $itemBookTypeString = "'" . implode("','", $this->itemBookType) . "'";

        $itemsCategories = ItemsCategoryCount::find();
        $itemsNums = ItemsNumsBook::findFirst();
        $booksNums = $itemsNums->item_count;
        $arrayItemCategory = array();

        // var_dump($itemsCategories);
        foreach ($itemsCategories as $itemsCategory) {
            $percentCategory = number_format(( (int)$itemsCategory->cat_count/$booksNums) * 100, 2);

            $arrayItemCategory[] = "['" . $itemsCategory->code . " " . $itemsCategory->name . "', " . $percentCategory . "]";
        }

        return $data = array(
            'itemsCategory' => implode(",", $arrayItemCategory),
        );
    }

}

