<?php

namespace taroxx\wsapi\models;

/**
 * This is the ActiveQuery class for [[AutoModification]].
 *
 * @see AutoModification
 */
class AutoModificationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return AutoModification[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return AutoModification|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
