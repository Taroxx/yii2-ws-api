<?php
namespace taroxx\wsapi;
use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface{
    //Метод, который вызывается автоматически при каждом запросе
    public function bootstrap($app)
    {
        //Правила маршрутизации
//        $app->getUrlManager()->addRules([
//            'test' => 'mytest/test/index',
//        ], false);
        /*
         * Регистрация модуля в приложении
         * (вместо указания в файле frontend/config/main.php
         */
//        $app->setModule('wsapi', 'taroxx\wsapi\Module');
    }
}