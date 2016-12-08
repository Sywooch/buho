<?php

$params = require(__DIR__ . '/params.php');

$config = [
	'id'         => 'basic',
	'basePath'   => dirname(__DIR__),
	'bootstrap'  => ['log'],
	'homeUrl'    => '/',
	'components' => [
		'view'         => [
			'class'     => 'app\components\BaseView',
			'renderers' => [
				'twig' => [
					'class'     => 'yii\twig\ViewRenderer',
					'cachePath' => '@runtime/Twig/cache',
					// Array of twig options:
					'options'   => [
						'auto_reload' => TRUE,
					],
					'filters' => [
						'view' => 'view'
					],
					'globals'   => [
						'html'  => '\yii\helpers\Html',
						'Url'  => '\yii\helpers\Url',
						'asset' => '\app\assets\AppAsset',
						'Yii'   => 'Yii',
						'Pages' => '\app\models\Pages',

					],
                    'functions' => [
                        //	короткий алиас для переводчика
                        't' => 'Yii::t',
                    ],
					'uses'      => ['yii\bootstrap'],
				],
				// ...
			],
		],
		'request'      => [
			// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
			'cookieValidationKey' => '118jqPtEKatc3Wd1234pdYETeVrixGwKiP0Q',
			'baseUrl'             => '/',    // for multiLang
			'class'               => 'app\components\LangRequest' // for multiLang
		],
		'cache'        => [
			'class' => 'yii\caching\FileCache',
		],
		'gui'        => [
			'class' => 'app\components\GuiHelper',
		],
		'user'         => [
			'identityClass'   => 'app\models\User\User',
			'enableAutoLogin' => TRUE,
		],
		'errorHandler' => [
			'errorAction' => 'content/error',
		],
		'mailer'       => [
			'class'            => 'yii\swiftmailer\Mailer',
			// send all mails to a file by default. You have to set
			// 'useFileTransport' to false and configure a transport
			// for the mailer to send real emails.
			//'useFileTransport' => TRUE,
		],
		'log'          => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets'    => [
				[
					'class'  => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'db'           => require(__DIR__ . '/db.php'),
		'urlManager'   => [
			'class'               => 'app\components\LangUrlManager', // for multiLang
			'enablePrettyUrl'     => TRUE,
			'showScriptName'      => FALSE,
			'enableStrictParsing' => FALSE,
			'rules'               => [
				[
					'pattern' => '',
					'route' => 'content/index',
					'suffix' => ''
				],
				[
					'pattern' => 'search.html',
					'route' => 'catalog/search',
					'suffix' => ''
				],
				[
					'pattern' => 'contacts.html',
					'route' => 'content/contacts',
					'suffix' => ''
				],
                [
                    'pattern' => '<action:(partners)>',
                    'route' => 'content/<action>',
                    'suffix' => '.html'
                ],
                [
                    'pattern' => '<type:(news|article)>',
                    'route' => 'content/news',
                    'suffix' => '.html'
                ],
				[
					'pattern' => '<alias>',
					'route' => 'content/view',
					'suffix' => ''
				],
				[
					'pattern' => '<type:(news|article)>/<alias>',
					'route' => 'content/news-view',
					'suffix' => '.html'
				],
				[
					'pattern' => 'catalog/<action:(actions|discounts)>/<page:\d+>',
					'route' => 'catalog/<action>',
					'suffix' => '/',
					'defaults' => ['page' => 1]
				],
				[
					'pattern' => 'catalog/<alias>/filter=<filter:([\d\-]+)>/price=<price:\d+\-\d+>/<page:\d+>',
					'route' => 'catalog/index',
					'suffix' => '/',
					'defaults' => ['page' => 1]
				],
				[
					'pattern' => 'catalog/<alias>/filter=<filter:([\d\-]+)>/<page:\d+>',
					'route' => 'catalog/index',
					'suffix' => '/',
					'defaults' => ['page' => 1]
				],
				[
					'pattern' => 'catalog/<alias>/price=<price:([\d\-]+)>/<page:\d+>',
					'route' => 'catalog/index',
					'suffix' => '/',
					'defaults' => ['page' => 1]
				],
				[
					'pattern' => 'catalog/<alias>/<page:\d+>',
					'route' => 'catalog/index',
					'suffix' => '/',
					'defaults' => ['page' => 1]
				],
				[
					'pattern' => 'product/<alias>',
					'route' => 'product/index',
					'suffix' => '/',
				],
                [
                    'pattern' => 'product/image/<alias>/<number>',
                    'route' => 'product/image',
                    'suffix' => '/',
                    'defaults' => ['number' => 0]
                ],
				[
					'pattern' => '<_c>/<_a>',
					'route' => '<_c>/<_a>',
					'suffix' => '/',
				],

				/*
				'gii'                                                => 'gii',
				'gii/<controller:\w+>'                               => 'gii/<controller>',
				'gii/<controller:\w+>/<action:\w+>'                  => 'gii/<controller>/<action>',
				''                                                   => 'content/index',
				'content/callback-contacts.rtr'                      => 'content/callback-contacts',
				'company.html'                                       => 'content/company',
				'contacts.html'                                      => 'content/contacts',
				'gallery.html'                                       => 'content/gallery',
				'search.html'                                        => 'catalog/search',
				'news.html'                                          => 'content/news',
				'news/<alias>.html'                                  => 'content/news-view',
				'cart.html'                                          => 'cart/index',
				'catalog/<alias>.html/filter=<filter>/price=<price>' => 'catalog/index',
				'catalog/<alias>.html/filter=<filter>'               => 'catalog/index',
				'catalog/<alias>.html'                               => 'catalog/index',
				'product/<alias>.html'                               => 'product/index',
				'<alias>.html'                                       => 'content/view',
				'<_c>'                                               => '<_c>/index',
				'catalog/<alias>'                                    => 'catalog/index',
				'catalog/<cat_alias>/<prod_alias>'                   => 'catalog/product',
				'cart/<_a>'                                          => 'cart/<_a>',
				'<_c>/<_a>'                                          => '<_c>/<_a>',
				*/
			],
		],
		'assetManager' => [
			'bundles' => [
				'yii\web\JqueryAsset'                => [
					'sourcePath' => NULL,
					'basePath'   => '@webroot',
					'baseUrl'    => '@web',
					'js'         => [
						//'js/vendor/jquery-1.8.2.min.js',
					],
				],
				'yii\bootstrap\BootstrapPluginAsset' => [
					'sourcePath' => NULL,
					'basePath'   => '@webroot',
					'baseUrl'    => '@web',
					'js'         => [],
				],
				'yii\bootstrap\BootstrapAsset'       => [
					'sourcePath' => NULL,
					'basePath'   => '@webroot',
					'baseUrl'    => '@web',
					'css'        => [

					],
				],
				'yii\web\YiiAsset'                   => [
					'sourcePath' => NULL,
					'basePath'   => '@webroot',
					'baseUrl'    => '@web',
					'js'         => [],
				],

			],
		],
		'language'     => 'ru-RU',
		'i18n'         => [
			'translations' => [
				'*' => [
					'class'    => 'yii\i18n\PhpMessageSource',
					'basePath' => '@app/messages',
				],
			],
		],
	],
	'params'     => $params,
];

if (YII_ENV_DEV)
{
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][] = 'debug';
	$config['modules']['debug'] = [
		'class'      => 'yii\debug\Module',
		'allowedIPs' => ['127.0.0.1', '37.115.80.189'],
	];

	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class'      => 'yii\gii\Module',
		'allowedIPs' => ['127.0.0.1', '37.115.80.189'],
	];
}

return $config;
