<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
		'js/jquery-1.8.2.min.js',
		'js/jquery_wp.js',
		'js/jquery-migrate.min.js',
		'js/fotorama.js',
		'js/fotorama-wp.js',
		'js/core.min.js',
		'js/widget.min.js',
		'js/mouse.min.js',
                'js/slider.min.js',
                'js/sortable.min.js',
                'js/wp-embed.min.js',
                'js/custom.js',
    ];
    public $css = [
                'css/styles.css',
		'css/fotorama.css',		
		'css/slick.css',
		'css/columns.min.css',
                'css/shortcodes.css',
                'css/page_templates.css',
                'css/fotorama-lib.css',
    ];      
    public $depends = [
       # 'yii\web\YiiAsset',
       # 'yii\bootstrap\BootstrapAsset',
    ];
}
