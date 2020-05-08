<?php

namespace taroxx\wsapi\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "api_auto_modification".
 *
 * @property int $id
 * @property int $brand_id
 * @property int $model_id
 * @property string $name
 * @property string $body
 * @property string $title
 * @property int $release_year
 * @property string $engine_displacement
 * @property string|null $power
 * @property string $center_bore
 * @property string $pcd
 * @property int $lug_count
 * @property string $lug_size
 * @property int $lug_type
 * @property string|null $market
 * @property string $slug
 * @property int|null $is_hand
 * @property int|null $is_active_position
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AutoBrand $brand
 * @property AutoModel $model
 * @property AutoModificationTire[] $tires
 * @property AutoModificationWheel[] $wheels
 */
class AutoModification extends \yii\db\ActiveRecord
{

    /**
     * Гайка
     */
    const LUG_TYPE_NUT = 0;

    /**
     * Болт
     */
    const LUG_TYPE_BOLT = 1;

    /**
     * Болт/гайка
     */
    const LUG_TYPE_BOLT_NUT = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
//        return 'api_auto_modification';
        $wsapiModule = Yii::$app->getModule('wsapi');
        $modificationTable = isset($wsapiModule->params['modificationTable']) ? $wsapiModule->params['modificationTable'] : 'api_auto_modification';
        return $modificationTable;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brand_id', 'model_id', 'name', 'body', 'title', 'release_year', 'engine_displacement', 'center_bore', 'pcd', 'lug_count', 'lug_size', 'lug_type', 'slug'], 'required'],
            [['brand_id', 'model_id', 'release_year', 'lug_count', 'lug_type', 'is_hand', 'is_active_position'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'body', 'title', 'engine_displacement', 'power', 'center_bore', 'pcd', 'lug_size', 'market', 'slug'], 'string', 'max' => 255],
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
            'model_id' => 'Model ID',
            'name' => 'Name',
            'body' => 'Body',
            'title' => 'Title',
            'release_year' => 'Release Year',
            'engine_displacement' => 'Engine Displacement',
            'power' => 'Power',
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
     * @return AutoModificationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AutoModificationQuery(get_called_class());
    }

    public function behaviors()
    {
        return [[
            'class' => SluggableBehavior::className(),
            'attribute' => ['brand.name', 'model.name', 'name', 'release_year'],
            'ensureUnique' => true,
        ]];
    }

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
    public function getModel()
    {
        return $this->hasOne(AutoModel::className(), ['id' => 'model_id']);
    }

    public function getTires($onlyFrontAxles = true)
    {
        $query = $this->hasMany(AutoModificationTire::className(), ['modification_id' => 'id'])->orderBy('diameter');
        if ($onlyFrontAxles) {
            $query->where(['axle' => Axle::TYPE_FRONT]);
        }
        return $query;
    }

    public function getWheels($onlyFrontAxles = true)
    {
        $query = $this->hasMany(AutoModificationWheel::className(), ['modification_id' => 'id'])->orderBy('diameter');
        if ($onlyFrontAxles) {
            $query->where(['axle' => Axle::TYPE_FRONT]);
        }
        return $query;
    }

    public function getLugList()
    {
        return [
            self::LUG_TYPE_NUT => 'Гайка',
            self::LUG_TYPE_BOLT => 'Болт',
            self::LUG_TYPE_BOLT_NUT => 'Гайка/болт'
        ];
    }

    public function getLugTitle()
    {
        $data = $this->getLugList();

        return (isset($data[$this->lug_type]) ? $data[$this->lug_type] : '---');
    }

    public function getTitle($release_year = true)
    {
        $title = $this->brand->name . ' ' . $this->model->name . ' ' . $this->name;
        if ($release_year) {
            $title .= ' ' . $this->release_year . ' года';
        }

        return $title;
    }

    public static function getList($model_id = null)
    {
        $models = ($model_id === null ? self::find()->orderBy('name')->all() : self::find()->orderBy('name ASC, release_year ASC')->where(['model_id' => $model_id])->all());

        return ArrayHelper::map(
            $models,
            'id',
            function ($x) {
                return $x->name . ' ' . $x->release_year . 'г.';
            }
        );
    }


}
