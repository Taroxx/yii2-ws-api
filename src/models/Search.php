<?php


namespace taroxx\wsapi\models;
use yii\db\ActiveRecord;


class Search extends ActiveRecord
{

    public static function getBrandByName($name)
    {
        return AutoBrand::findOne(['name' => $name]);
    }

    public static function getBrandBySlug($slug)
    {
        return AutoBrand::findOne(['slug' => $slug]);
    }

    public static function getModelBySlug($slug)
    {
        return AutoModel::findOne(['slug' => $slug]);
    }
}