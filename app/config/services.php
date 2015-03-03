<?php
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Crypt;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
// use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Mvc\Model\Metadata\Files as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Direct as Flash;
// use Base\Plugins\Security as Security;


use Phoenix\Auth\Auth;
use Phoenix\Acl\Acl;
use Phoenix\Library\Mail\Mail;

use Phoenix\Library\Security\Security;
// use Phoenix\Library\PHPExcel\PHPExcel;

use Phoenix\Library\Elements\Elements;
use Phoenix\Library\DateTime\DateTime;
use Phoenix\Library\Import\Import;

use Whoops\Provider\Phalcon\WhoopsServiceProvider;

use Mailgun\Mailgun;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();


/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);


/**
 * Setting up the view component
 */



$di->set('view', function () use ($config) {

    $view = new View();

    $view->setViewsDir($config->application->viewsDir);
    $view->setLayoutsDir('../views/common/layouts');
    $view->setTemplateAfter('flat');
    $view->setLayout('main');
    

    $view->registerEngines(array(
        // '.volt' => function ($view, $di) use ($config) {

        //     $volt = new VoltEngine($view, $di);

        //     $volt->setOptions(array(
        //         'compiledPath' => $config->application->cacheDir,
        //         'compiledSeparator' => '_'
        //     ));

        //     return $volt;
        // },
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ));

    return $view;
}, true);

// //Set the views cache service
// $di->set('viewCache', function(){

//     //Cache data for one day by default
//     $frontCache = new Phalcon\Cache\Frontend\Output(array(
//         "lifetime" => 2592000
//     ));

//     //File backend settings
//     $cache = new Phalcon\Cache\Backend\File($frontCache, array(
//         "cacheDir" => __DIR__."/app/cache/",
//         "prefix" => "php"
//     ));

//     return $cache;
// });


/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function () use ($config) {
    return new DbAdapter(array(
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname
    ));
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
// $di->set('modelsMetadata', function () use ($config) {
//     return new MetaDataAdapter(array(
//         'metaDataDir' => $config->application->cacheDir . 'metaData/'
//     ));
// });


/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
// $di->set('modelsMetadata', function () {
//     return new MetaDataAdapter();
// });

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function() use ($config) {
    if (isset($config->models->metadata)) {
        $metaDataConfig = $config->models->metadata;
        $metadataAdapter = 'Phalcon\Mvc\Model\Metadata\\'.$metaDataConfig->adapter;
        return new $metadataAdapter();
    }
    return new Phalcon\Mvc\Model\Metadata\Memory();
});


/**
 * Start the session the first time some component request the session service
 */
$di->set('session', function () {
    $session = new SessionAdapter(
        array(
            'uniqueId' => 'phoenix-saat'
        )
    );
    $session->start();

    return $session;
});

/**
 * Crypt service
 */
$di->set('crypt', function () use ($config) {
    $crypt = new Crypt();
    $crypt->setKey($config->application->cryptSalt);
    return $crypt;
});


/**
 * We register the events manager Dispatcher use a default namespace
 */
$di->set('dispatcher', function() use ($di) {

    $eventsManager = $di->getShared('eventsManager');

    $security = new Security($di);
    $eventsManager->attach('dispatch', $security);

    $dispatcher = new Dispatcher();
    $dispatcher->setDefaultNamespace('Phoenix\Controllers');
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;
}, true);

/**
 * Dispatcher use a default namespace
 */
// $di->set('dispatcher', function () {
//     $dispatcher = new Dispatcher();
//     $dispatcher->setDefaultNamespace('Phoenix\Controllers');
//     return $dispatcher;
// });


/**
 * Load router from external file
 */
$di->set('router', function(){
    require __DIR__.'/routes.php';
    return $router;
}, true);


/**
 * Register the flash service with custom CSS classes
 */

class MyFlash extends Phalcon\Flash\Direct
{
    public function message($type, $message)
    {
        $message .= ' <button type="button" class="close" data-dismiss="alert">×</button>';
        parent::message($type, $message);
    }
}

$di->set('flash', function(){
    $flash = new MyFlash(array(
        'error' => 'alert alert-danger',
        'success' => 'alert alert-success alert-dismissable',
        'notice' => 'alert alert-info',
        'warning' => 'alert alert-warning',
    ));
    return $flash;
});
/**
 * Custom authentication component
 */
// $di->set('auth', function () {
//     return new Auth();
// });

/**
 * Mail service uses AmazonSES
 */
$di->set('mail', function () {
    return new Mail();
});

/**
 * Access Control List
 */
// $di->set('acl', function () {
//     return new Acl();
// });

$di->setShared('version', $config->application->version);
$di->setShared('baseUrl', $config->application->baseUri);


$di->set('crumbs', function() {
    require __DIR__.'/../plugins/Breadcrumbs/Breadcrumbs.php';
    return new \Phalib\Breadcrumbs\Breadcrumbs();
});

/**
 * Register a user component
 */
$di->set('elements', function(){
    return new Elements();
});

$di->set('tcpdf', function() {
    include __DIR__ . "/../../vendor/autoload.php";
    return new TCPDF();
});

// $di->set('timeAgos', function() {
//     include __DIR__ . "/../../vendor/autoload.php";
//     return new TimeAgo('Asia/Bangkok');
// });

$di->set('datetime', function(){
    return new DateTime();
});

$di->set('moment', function() {
    include __DIR__ . "/../../vendor/autoload.php";
    $date = new ExpressiveDate(null, 'Asia/Bangkok');
    $date->setDefaultDateFormat('Y-m-d H:i:s');
    return $date;
});

$di->set('Highchart', function(){
    include __DIR__ . "/../../vendor/autoload.php";
    return new Ghunti\HighchartsPHP\Highchart();
});

$di->set('HighchartJsExpr', function(){
    include __DIR__ . "/../../vendor/autoload.php";
    return new Ghunti\HighchartsPHP\HighchartJsExpr("function() {
            return '' + this.x +': '+ this.y +'';}");
});

$di->set('Mysqldump', function() use ($config) {
    include __DIR__ . "/../../vendor/autoload.php";
    $dumpSettings = array(
            'include-tables' => array(),
            'exclude-tables' => array(),
            'compress' => 'None',
            'no-data' => false,
            'add-drop-table' => true,
            'single-transaction' => true,
            'lock-tables' => false,
            'add-locks' => true,
            'extended-insert' => true,
            'disable-keys' => true,
            'disable-foreign-keys-check' => false,
            'where' => '',
            'no-create-info' => false,
            'skip-triggers' => false,
            'add-drop-trigger' => true,
            'hex-blob' => true,
            'databases' => false,
            'add-drop-database' => false
        );

    return new Ifsnop\Mysqldump\Mysqldump(
        $db = $config->database->dbname, 
        $user = $config->database->username, 
        $password = $config->database->password, 
        $host = $config->database->host,
        $type = $config->database->adapter,
        $dumpSettings
    );
});

// $di->set('PHPExcelOri', function(){
//     include __DIR__ . "/../../vendor/autoload.php";

//     $inputFileType = 'Excel5';
//     $inputFileName = './sampleData/example1.xls';
//     return PHPExcel_IOFactory::createReader('Excel5');
// });

// $di->set('PHPExcel', function(){
//     include __DIR__ . "/../../vendor/autoload.php";

//     $PHPExcel = new PHPExcel();
//     $PHPExcel_Writer_Excel2007 = new PHPExcel_Writer_Excel2007($PHPExcel);

//     $excel['PHPExcel'] = $PHPExcel;
//     $excel['PHPExcel_Writer_Excel2007'] = $PHPExcel_Writer_Excel2007;
//     return $PHPExcel;
// });

// $di->set('PHPExcel_Writer_Excel2007', function() use ($di) {
//     // include __DIR__ . "/../../vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007.php";
//     include __DIR__ . "/../../vendor/autoload.php";
//     return new PHPExcel_Writer_Excel2007(new PHPExcel());
// });


$di->set('import', function(){
    return new Import();
});

$di->set('whoops', function(){
    return new Whoops\Provider\Phalcon\WhoopsServiceProvider($di);
});

$di->set('dompdf', function(){
    include __DIR__ . "/../../vendor/autoload.php";
    define('DOMPDF_ENABLE_AUTOLOAD', false);
    require_once __DIR__ . "/../../vendor/dompdf/dompdf/dompdf_config.inc.php";
    return new DOMPDF();
});

$di->set('QueryBuilder', function(){
    include __DIR__ . "/../../vendor/autoload.php";

    return $builder = new \DataTables\Adapters\QueryBuilder();
});