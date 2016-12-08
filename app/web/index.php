<?php

define('ROOT', realpath(__DIR__.'/../../'));
define('WEB', __DIR__);

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();

function view($str)
{
	echo '<pre style="width:100%; clear:both; padding:10px; background-color:#EEE;">'.print_r($str, 1).'</pre>';
}
