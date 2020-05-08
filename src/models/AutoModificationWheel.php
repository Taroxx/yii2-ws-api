<?php

namespace taroxx\wsapi\models;

use Yii;

/**
 * This is the model class for table "api_auto_modification_wheel".
 *
 * @property int $id
 * @property int $modification_id
 * @property float $width
 * @property float $offset
 * @property float $diameter
 * @property int|null $is_factory
 * @property int|null $axle
 * @property int|null $pair_id
 * @property int|null $manually_added
 * @property int|null $is_active_position
 * @property string $created_at
 * @property string $updated_at
 */
class AutoModificationWheel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
//        return 'api_auto_modification_wheel';
        $wsapiModule = Yii::$app->getModule('wsapi');
        $modificationWheelTable = isset($wsapiModule->params['modificationWheelTable']) ? $wsapiModule->params['modificationWheelTable'] : 'api_auto_modification_wheel';
        return $modificationWheelTable;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['modification_id', 'width', 'offset', 'diameter'], 'required'],
            [['modification_id', 'is_factory', 'axle', 'pair_id', 'manually_added', 'is_active_position'], 'integer'],
            [['width', 'offset', 'diameter'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
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
            'offset' => 'Offset',
            'diameter' => 'Diameter',
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
        return sprintf("%gx%g ET%g", $this->width, $this->diameter, $this->offset);
    }
}
