<?php

namespace klisl\mytest;

use yii\web\AssetBundle;

class TestsAssetsBundle extends AssetBundle
{

    public $sourcePath = '@vendor/klisl/yii2-mytest/assets';

    public $css = [
        'css/style.css'
    ];
}