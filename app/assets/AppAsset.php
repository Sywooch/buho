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
    public $css = [
        'css/bootstrap.css',
		'css/magnific-popup.css',
		'css/styles.css',
		'css/jquery.bxslider.css',
		'css/responsive.css',
    ];
    public $js = [
		'js/jquery-1.8.2.min.js',
		'js/jquery-ui.js',
		'js/jquery.magnific-popup.min.js',
		'js/jquery.bxslider.min.js',
		'js/jquery.maskedinput.min.js',
		'js/SmoothScroll.js',
		'js/gui.min.js',
		'js/custom.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
