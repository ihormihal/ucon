<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '';
    public $css = [
        'assets/plugins/font-awesome/css/font-awesome.min.css',
        'assets/plugins/material-icons/css/material-icons.css',
        'assets/plugins/fancybox/css/jquery.fancybox.css',
        'assets/plugins/owl/css/owl.carousel.css',
        'assets/plugins/toastr/toastr.min.css',
        'assets/plugins/fotorama/fotorama.css',
        'assets/css/style.css',
        'assets/css/animate.css',
        'assets/css/fade-slider.css',
    ];
    public $js = [
        'http://maps.google.com/maps/api/js?key=AIzaSyCtgv6ZsLiaUTE0xlRioaFXkGxcTfmXcQc&sensor=false',
        'assets/plugins/jquery/jquery-2.1.4.min.js',
        'assets/plugins/fancybox/js/jquery.fancybox.pack.js',
        'assets/plugins/owl/js/owl.carousel.min.js',
        'assets/plugins/toastr/toastr.min.js',
        'assets/plugins/fotorama/fotorama.js',
        'assets/js/framework/owl.js',
        'assets/js/framework/ripple.js',
        'assets/js/framework/navbar.js',
        'assets/js/framework/im-dropdown.js',
        'assets/js/framework/im-select.js',
        'assets/js/framework/im-parallax.js',
        'assets/js/framework/fileinput.js',
        'assets/js/scripts.js',

        //angular
        'assets/app/lib/angular.min.js',
        'assets/app/lib/ng-file-upload.min.js',
        'assets/app/modules/im-datatable.js',
        'assets/app/modules/im-img-uploader.js',
        'assets/app/modules/im-progress.js',
        'assets/app/modules/im-autocomplete.js',
        'assets/app/modules/im-datepicker.js',
        'assets/app/app.js',
    ];
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
