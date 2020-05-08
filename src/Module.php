<?php
namespace taroxx\wsapi;

use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    public $controllerNamespace = 'taroxx\wsapi\controllers';
    public $key;

    public function init()
    {
        parent::init();
    }
}
