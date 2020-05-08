<?php

namespace taroxx\wsapi\models;

use Yii;

/**
 * This is the model class for table "api_auto_modification_tire".
 *
 * @property int $id
 * @property int $modification_id
 * @property float $width
 * @property float $height
 * @property float $diameter
 * @property int|null $load_index
 * @property string|null $speed_rating
 * @property string|null $additional_param
 * @property int|null $is_factory
 * @property int|null $axle
 * @property int|null $pair_id
 * @property int|null $manually_added
 * @property int|null $is_active_position
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AutoModification $modification
 * @property AutoModificationTire $pair
 */
class AutoModificationTire extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
//        return 'api_auto_modification_tire';
        $wsapiModule = Yii::$app->getModule('wsapi');
        $modificationTireTable = isset($wsapiModule->params['modificationTireTable']) ? $wsapiModule->params['modificationTireTable'] : 'api_auto_modification_tire';
        return $modificationTireTable;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['modification_id', 'width', 'height', 'diameter'], 'required'],
            [['modification_id', 'load_index', 'is_factory', 'axle', 'pair_id', 'manually_added', 'is_active_position'], 'integer'],
            [['width', 'height', 'diameter'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['speed_rating', 'additional_param'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'modification_id' => 'Modification ID',
            'width' => 'Width',
            'height' => 'Height',
            'diameter' => 'Diameter',
            'load_index' => 'Load Index',
            'speed_rating' => 'Speed Rating',
            'additional_param' => 'Additional Param',
            'is_factory' => 'Is Factory',
            'axle' => 'Axle',
            'pair_id' => 'Pair ID',
            'manually_added' => 'Manually Added',
            'is_active_position' => 'Is Active Position',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModification()
    {
        return $this->hasOne(AutoModification::className(), ['id' => 'modification_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPair()
    {
        return $this->hasOne(self::className(), ['id' => 'pair_id']);
    }

    public function getTitle()
    {
        return $this->width . '/' . $this->height . ' R' . $this->diameter;
    }
}
