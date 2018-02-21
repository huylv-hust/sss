<?php
namespace backend\assets;

use yii\web\AssetBundle;

class PreviewAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'font-awesome/css/font-awesome.min.css',
        'css/preview.css'
    ];
    public $js = [
        'js/jquery-2.1.4.min.js',
        'js/bootstrap.min.js',
    ];
    public $depends = [
    ];
}
