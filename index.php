<?php
// change the following paths if necessary
define('API_DOMAIN','/5dhlnew/');

define('API_SEARCH_COMINFO','api/agent.api.php?do=searchCom');

require_once( dirname(__FILE__).'/protected/globals.php' );

$yii=dirname(__FILE__).'/../yii-download/yii-1.1.3.r2247/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
