<?php

namespace taroxx\wsapi\models;

/**
 * This is the ActiveQuery class for [[AutoModel]].
 *
 * @see AutoModel
 */
class AutoModelQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return AutoModel[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return AutoModel|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
