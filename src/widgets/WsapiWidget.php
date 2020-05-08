<?php

namespace taroxx\wsapi\widgets;

use \taroxx\wsapi\components\WheelSizeAPI;
use \taroxx\wsapi\WsapiAsset;


class WsapiWidget extends \yii\base\Widget
{

    public $test = 'test';
    public $componentName = 'wsapi';
    public $wsapi;
    public $view = 'wsapi-widget';

    public function init()
    {
        parent::init();
        $this->registerAssetBundle();
//        $this->wsapi = $this->getComponent();
    }

    public function run()
    {
        return $this->render($this->view, ['model' => $this->wsapi]);
    }

    public function registerAssetBundle()
    {
        $view = $this->getView();
        WsapiAsset::register($view);
    }

    /**
     * @return WheelSizeAPI;
     */
    private function getComponent()
    {
        return \Yii::$app->get($this->componentName);
    }
}
