<?php

namespace taroxx\wsapi\models;

use Yii;
use yii\helpers\ArrayHelper;

class Axle
{
    const TYPE_FRONT = 0;
    const TYPE_REAR = 1;

    public static function getTitle($type)
    {
        return ArrayHelper::getValue(self::getList(), $type, '');
    }

    public static function getList()
    {
        return [
            self::TYPE_FRONT => 'Передняя',
            self::TYPE_REAR => 'Задняя',
        ];
    }
}
