<?php
header('Content-type:text/html;charset=utf-8');
function pprint($data){
    print "<pre>";
    print_r($data);
    print "</pre>";
}
function dprint($data){
    print "<pre>";
    print_r($data);
    print "</pre>";
    exit();
}

date_default_timezone_set('PRC');


if(isset($_SERVER['argv'])) {
    if(isset($_SERVER['argv'][1])) {
        parse_str($_SERVER['argv'][1], $_GET);
    }
}

// change the following paths if necessary
$yii=dirname(__FILE__).'/../yii/yii.php';
$config=dirname(__FILE__).'/../protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',false);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
