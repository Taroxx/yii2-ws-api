<?php

namespace taroxx\wsapi\models;

/**
 * This is the ActiveQuery class for [[AutoBrand]].
 *
 * @see AutoBrand
 */
class AutoBrandQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return AutoBrand[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return AutoBrand|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
