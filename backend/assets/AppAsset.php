<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
// class AppAsset extends AssetBundle
// {
//     public $basePath = '@webroot';
//     public $baseUrl = '@web';
//     public $css = [
//         'css/site.css',
//     ];
//     public $js = [
//     ];
//     public $depends = [
//         'yii\web\YiiAsset',
//         'yii\bootstrap\BootstrapAsset',
//     ];
// }
class AppAsset extends AssetBundle
{
    
    public $basePath = '@webroot';
    public $baseUrl = '';
    public $css = [
        'design/plugins/font-awesome/css/font-awesome.min.css',
        'design/plugins/material-icons/css/material-icons.css',
        'design/plugins/fancybox/css/jquery.fancybox.css',
        'design/plugins/owl/css/owl.carousel.css',
        'design/plugins/toastr/toastr.min.css',
        'design/css/style.css',
        'design/css/animate.css'
    ];
    public $js = [
        'design/plugins/jquery/jquery-2.1.4.min.js',
        'design/plugins/fancybox/js/jquery.fancybox.pack.js',
        'design/plugins/owl/js/owl.carousel.min.js',
        'design/plugins/toastr/toastr.min.js',
        'design/js/framework/owl.js',
        'design/js/framework/ripple.js',
        'design/js/framework/navbar.js',
        'design/js/framework/im-dropdown.js',
        'design/js/framework/im-select.js',
        'design/js/framework/im-parallax.js',
        'design/js/framework/fileinput.js',
        'design/js/scripts.js',

        //angular
        'design/app/lib/angular.min.js',
        'design/app/lib/ng-file-upload.min.js',
        'design/app/modules/im-datatable.js',
        'design/app/modules/im-img-uploader.js',
        'design/app/modules/im-progress.js',
        'design/app/modules/im-autocomplete.js',
        'design/app/app.js',
    ];
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}

