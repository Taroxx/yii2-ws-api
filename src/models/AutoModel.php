<?php

namespace taroxx\wsapi\models;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "api_auto_model".
 *
 * @property int $id
 * @property int $brand_id
 * @property string $model_name
 * @property int $year_start
 * @property int $year_end
 * @property string $body
 * @property string $name
 * @property string|null $car
 * @property string|null $center_bore
 * @property string|null $pcd
 * @property int|null $lug_count
 * @property string|null $lug_size
 * @property string|null $lug_type
 * @property string|null $market
 * @property string $slug
 * @property int|null $is_hand
 * @property int|null $is_active_position
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AutoBrand $brand
 * @property AutoModification[] $modifications
 */
class AutoModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        $wsapiModule = Yii::$app->getModule('wsapi');
        $modelTable = isset($wsapiModule->params['modelTable']) ? $wsapiModule->params['modelTable'] : 'api_auto_model';
//        return 'api_auto_model';
        return $modelTable;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brand_id', 'model_name', 'year_start', 'year_end', 'name', 'body', 'slug'], 'required'],
            [['brand_id', 'year_start', 'year_end', 'lug_count', 'is_hand', 'is_active_position'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['model_name', 'body', 'name', 'car', 'center_bore', 'pcd', 'lug_size', 'lug_type', 'market', 'slug'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand_id' => 'Brand ID',
            'model_name' => 'Model Name',
            'year_start' => 'Year Start',
            'year_end' => 'Year End',
            'body' => 'Body',
            'name' => 'Name',
            'car' => 'Car',
            'center_bore' => 'Center Bore',
            'pcd' => 'Pcd',
            'lug_count' => 'Lug Count',
            'lug_size' => 'Lug Size',
            'lug_type' => 'Lug Type',
            'market' => 'Market',
            'slug' => 'Slug',
            'is_hand' => 'Is Hand',
            'is_active_position' => 'Is Active Position',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return AutoModelQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AutoModelQuery(get_called_class());
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
    public function getBrand()
    {
        return $this->hasOne(AutoBrand::className(), ['id' => 'brand_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModifications()
    {
        return $this->hasMany(AutoModification::className(), ['model_id' => 'id']);
    }

    public function getTitle()
    {
        return $this->brand->name . ' ' . $this->name;
    }
}
