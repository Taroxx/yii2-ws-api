<?php

namespace taroxx\wsapi\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\db\Query;
use yii\helpers\Url;

/**
 * This is the model class for table "api_auto_brand".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $logo
 * @property int|null $is_hand
 * @property int|null $is_active_position
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AutoModel[] $models
 * @property AutoModification[] $modifications
 */
class AutoBrand extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        $wsapiModule = Yii::$app->getModule('wsapi');
        $brandTable = isset($wsapiModule->params['brandTable']) ? $wsapiModule->params['brandTable'] : 'api_auto_brand';
//        return 'api_auto_brand';
        return $brandTable;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['is_hand', 'is_active_position'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'logo', 'slug'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'logo' => 'Logo',
            'slug' => 'Slug',
            'is_hand' => 'Is Hand',
            'is_active_position' => 'Is Active Position',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
//    public function getBrandByName()
//    {
//        $query = new Query();
//
//        $query->
//    }

//    public function addNewBrand($brand)
//    {
//        $this->name = $brand;
//        $this->save();
//    }

    /**
     * {@inheritdoc}
     * @return AutoBrandQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AutoBrandQuery(get_called_class());
    }

//    public function behaviors()
//    {
//        return [
//            [
//                'class' => SluggableBehavior::className(),
//                'attribute' => ['name'],
//                'ensureUnique' => true,
//            ]
//        ];
//    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModels()
    {
        return $this->hasMany(AutoModel::className(), ['brand_id' => 'id'])->orderBy('name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModifications()
    {
        return $this->hasMany(AutoModification::className(), ['brand_id' => 'id']);
    }

//    public function getUrl()
//    {
//        return Url::toRoute(['/path/to/controller/action', 'brand' => $this->name]);
//    }
}
