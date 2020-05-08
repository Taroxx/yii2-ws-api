<?php

namespace taroxx\wsapi;

use Yii;
use yii\web\AssetBundle;


class WsapiAsset extends AssetBundle
{

    public $sourcePath = '@vendor/taroxx/yii2-wsapi/assets';
    public $css = ['css/main.css'];
    public $js = ['js/main.js'];

//    public function init()
//    {
//        $this->sourcePath = __DIR__ . '/assets';
//        $this->css = ['css/main.css'];
//        $this->js = ['js/main.js'];
//    }

}