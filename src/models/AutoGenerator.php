<?php

namespace taroxx\wsapi\models;

use Yii;
use taroxx\wsapi\models\Search;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;

//use taroxx\wsapi\models\AutoBrand;

class AutoGenerator extends ActiveRecord
{

    private $brands;
    private $models;

    public $updatedBrands = 0;
    public $updatedModels = 0;
    public $updatedModifications = 0;

    private function getBrand($brandData, $returnBrand = true)
    {
        $brandKey = $brandData['slug'];
        if (!isset($this->brands[$brandKey])) {
            $brand = AutoBrand::findOne(['slug' => $brandData['slug']]);
            if (!$brand) {
                $brand = new AutoBrand();
                $brand->name = $brandData['name'];
                $brand->slug = $brandData['slug'];
                if ($brand->save()) {
//                    $this->updatedBrands++;
                }
            } else {
                if ($brand->name !== $brandData['name']) {
                    $brand->name = $brandData['name'];
                    $brand->update();
                }
            }
            $this->brands[$brandKey] = $brand;
        } else {
            $brand = $this->brands[$brandKey];
        }
        if ($returnBrand) return $brand;
    }

    private function getModel($modelData, $returnModel = true)
    {
//        $addinional_info = $modelData['additional_info'];

        $modelKey = $modelData['brand_id'] . '-' . $modelData['slug'];

        unset($modelData['additional_info']);
        unset($modelData['years']);
//        unset($modelData['tires']);
//        unset($modelData['wheels']);

//        $modelData['slug'] = $slug;
        $modelData['name'] = $modelData['model_name'] . ' (' . $modelData['body'] . ')';

        if (!isset($this->models[$modelKey])) {
            //        $queryModel = Search::getModelBySlug($slug);
            $model = AutoModel::findOne(['slug' => $modelData['slug'], 'brand_id' => $modelData['brand_id']]);

            if (!isset($model)) {
                $model = new AutoModel();
                $model->setAttributes($modelData);
                if (!$model->save()) {
                    throw new \yii\db\Exception('Exception when saving' . "\n" . print_r($model->getErrors(), true));
                } else $this->updatedModels++;
            } else {
                $model->updateAttributes($modelData);
            }

            $this->models[$modelKey] = $model;

        } else $model = $this->models[$modelKey];

        if ($returnModel) return $model;

    }


    public function processBrand($brandList)
    {
        foreach ($brandList as $slug => $name) {
            $brandData = [
                'slug' => $slug,
                'name' => $name
            ];
            self::getBrand($brandData, false);
        }
    }

    public function processModel($modelList)
    {
        foreach ($modelList as $modelData) {
            self::getModel($modelData, false);
        }
    }

    private function processModification($modificationList)
    {

        foreach ($modificationList as $modificationData) {

            $tiresData = $modificationData['tires'];
            $wheelsData = $modificationData['wheels'];
            $sizesSata = $modificationData['sizes'];


            unset($modificationData['tires']);
            unset($modificationData['wheels']);
            unset($modificationData['sizes']);

            foreach ($modificationData['years'] as $release_year) {

                $modification = AutoModification::findOne([
                        'brand_id' => $modificationData['brand_id'],
                        'model_id' => $modificationData['model_id'],
                        'name' => $modificationData['name'],
                        'release_year' => $release_year
                    ]
                );

                unset($modificationData['years']);

                $modificationData['release_year'] = $release_year;

                if (!$modification) {
                    $modification = new AutoModification();
                    $modification->setAttributes($modificationData);
                    if (!$modification->save()) {
                        throw new \yii\db\Exception('Exception when saving' . "\n" . print_r($modification->getErrors(), true));
                    } else {
                        $this->updatedModifications++;
                    }
                } else {
                    $modification->updateAttributes($modificationData);
                }
                $this->processSizes($sizesSata, $modification);
                unset($modification);
            }
        }
    }

    public function processSizes($data, $modification)
    {
        foreach ($data as $sizes){

            $isFactory = $sizes['is_factory'];
            $frontData = $sizes['front'];
            $rearData = $sizes['is_rear'] ? $sizes['rear'] : false;
            $frontDiameter = $frontData['diameter'];
            $rearDiameter = $rearData ? $rearData['diameter'] : false;

            $frontTireSize = ['diameter' => $frontDiameter] + $frontData['tires'];
            $frontWheelSize = ['diameter' => $frontDiameter] + $frontData['wheels'];

            if ($rearData) {
                $rearTireSize = ['diameter' => $rearDiameter] + $rearData['tires'];
                $rearWheelSize = ['diameter' => $rearDiameter] + $rearData['wheels'];

                $frontTire = self::addModificationTire($modification, $frontTireSize, $isFactory, Axle::TYPE_FRONT);
                $rearTire = self::addModificationTire($modification, $rearTireSize, $isFactory, Axle::TYPE_REAR);

                if ($frontTire && $rearTire) {
                    $rearTire->link('pair', $frontTire);
                    $frontTire->link('pair', $rearTire);
                }

                $frontWheel = self::addModificationWheel($modification, $frontWheelSize, $isFactory, Axle::TYPE_FRONT);
                $rearWheel = self::addModificationWheel($modification, $rearWheelSize, $isFactory, Axle::TYPE_REAR);

                if ($frontWheel && $rearWheel) {
                    $rearWheel->link('pair', $frontWheel);
                    $frontWheel->link('pair', $rearWheel);
                }

            }
            else {
                self::addModificationTire($modification, $frontTireSize, $isFactory, Axle::TYPE_FRONT);
                self::addModificationWheel($modification, $frontWheelSize, $isFactory, Axle::TYPE_FRONT);
            }

        }
        unset($modification);
    }

    private function addModificationTire($modification, $sizeData, $is_factory, $axle)
    {
        /** @var $modification AutoModification */
//        $sizeData = [
//            'width' => '',
//            'height' => '',
//            'diameter' => '',
//        ];


        $values = array_merge($sizeData, [
                'modification_id' => $modification->id,
                'is_factory' => $is_factory,
                'axle' => $axle
            ]
        );
        $modificationTire = AutoModificationTire::findOne($values);
        if (!$modificationTire) {
            $modificationTire = new AutoModificationTire();
            $modificationTire->setAttributes($values);
            $modification->link('tires', $modificationTire);
            return $modificationTire;
        }
        return false;

    }

    private function addModificationWheel($modification, $sizeData, $is_factory, $axle)
    {
        /** @var $modification AutoModification */
//        $sizeData = [
//            'width' => '',
//            'offset' => '',
//            'diameter' => '',
//        ];

        $values = array_merge($sizeData,
            [
                'modification_id' => $modification->id,
                'is_factory' => $is_factory,
                'axle' => $axle
            ]
        );
        $modificationWheel = AutoModificationWheel::findOne($values);
        if (!$modificationWheel) {
            $modificationWheel = new AutoModificationWheel();
            $modificationWheel->setAttributes($values);
            $modification->link('wheels', $modificationWheel);
            return $modificationWheel;
        }
        return false;

    }

    public function prepareModelData($common, $modifications)
    {

        $modelGenerations = [];

        foreach ($modifications as $modification) {
            $modGeneration = $modification['generation'];
            $body = $modGeneration['name'];
            $year_start = $modGeneration['start_year'];
            $year_end = $modGeneration['end_year'];
            $years = $modGeneration['years'];
            $body2 = isset($modification['body']) ? $modification['body'] : '';
            $setBody2 = true;

            if ($setBody2) $body = mb_strlen($body) !== 0 ? $body : $body2;

            $centerBore = [$modification['centre_bore']];
            $pcd = [$modification['pcd']];
            $lugCount = [$modification['stud_holes']];
            $lugSize = $modification['lock_text'];
            $lugType = '';

            if (isset($modification['lock_type'])) {
                $lockType = $modification['lock_type']; //lug_type 0 - гайка (nut), 1 - болт (bolt), 2 - гайка/болт (?)
                $lugType = $lockType === 'nut' ? '0' : ($lockType === 'bolt' ? '1' : '2');
            }

            if (isset($modelGenerations[$body]) && $modelGenerations[$body]['body'] === $body) {
//                if (count($modelGenerations[$body]) > 0){
                $prevSty = isset($modelGenerations[$body]['year_start']) ? $modelGenerations[$body]['year_start'] : $year_start;
                $prevEny = isset($modelGenerations[$body]['year_end']) ? $modelGenerations[$body]['year_end'] : $year_end;

                $year_start = min($year_start, $prevSty);
                $year_end = max($year_end, $prevEny);

                $prevPcd = isset($modelGenerations[$body]['pcd']) ? $modelGenerations[$body]['pcd'] : $pcd;
                $prevLugCount = isset($modelGenerations[$body]['lug_count']) ? $modelGenerations[$body]['lug_count'] : $lugCount;
                $prevCenterBore = isset($modelGenerations[$body]['center_bore']) ? $modelGenerations[$body]['center_bore'] : $centerBore;

                $pcd = array_merge($prevPcd, $pcd);
                $lugCount = array_merge($prevLugCount, $lugCount);
                $centerBore = array_merge($prevCenterBore, $centerBore);
//                }
            }

            $mostCenterBore = $this->takeBigger($centerBore);
            $slug = $common['model_name'] . '+' . $body . '+' . $year_start . '+' . $year_end;
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9=\s—+–-]+/u', '-', $slug));

            $modelGenerations[$body] = [
                'body' => $body,
                'year_start' => (int)$year_start,
                'year_end' => (int)$year_end,
                'center_bore' => array_unique($centerBore),
                'pcd' => array_unique($pcd),
                'lug_count' => array_unique($lugCount),
                'lug_size' => $lugSize,
                'lug_type' => $lugType,
                'slug' => $slug,
                'years' => $years,
                'additional_info' => [
                    'body2' => $body2,
                    'center_bore' => array_unique($centerBore),
                    'pcd' => array_unique($pcd),
                    'lug_count' => array_unique($lugCount),
                ]
            ];

        }

        foreach ($modelGenerations as $key => $gen) {
            $modelGenerations[$key] = array_merge($common, $gen);
            $modelGenerations[$key]['center_bore'] = (string)$this->takeBigger($gen['center_bore']);
            $modelGenerations[$key]['pcd'] = (string)$this->takeBigger($gen['pcd']);
            $modelGenerations[$key]['lug_count'] = (int)$this->takeBigger($gen['lug_count']);
        }

        return $modelGenerations;

    }

    public function prepareModificationData($common, $modifications, $modelData)
    {
        $modificationData = [];
        foreach ($modifications as $modification) {
            $modGeneration = $modification['generation'];
            $body = $modGeneration['name'];
            $body2 = isset($modification['body']) ? $modification['body'] : '';
            $setBody2 = true;

            if ($setBody2) $body = mb_strlen($body) !== 0 ? $body : $body2;

            $centerBore = $modification['centre_bore'];
            $pcd = $modification['pcd'];
            $lugCount = $modification['stud_holes'];
            $lugSize = $modification['lock_text'];
            $lugType = '';

            if (isset($modification['lock_type'])) {
                $lockType = $modification['lock_type']; //lug_type 0 - гайка (nut), 1 - болт (bolt), 2 - гайка/болт (?)
                $lugType = $lockType === 'nut' ? '0' : ($lockType === 'bolt' ? '1' : '2');
            }

            $modelSlug = $modelData[$body]['slug'];
            $modelKey = $common['brand_id'] . '-' . $modelSlug;
            $model = $this->models[$modelKey];

            /** @var $model AutoModel */

            $power = '';
            foreach ($modification['power'] as $unit => $value) {
                $power .= $value . ' ' . $unit . ' | ';
            }

            $power = mb_substr(trim($power), 0, -1);

            $sizes = $this->prepareSizesData($modification['wheels']);

            $modificationData[] = [
                'brand_id' => $common['brand_id'],
                'model_id' => $model->id,
                'name' => $modification['trim'],
                'body' => $body,
                'title' => $modification['trim'] . ' ' . $modification['engine_type'], // $modification['trim'].', '.Inflector::transliterate($modification['engine_type']),
                'release_year' => $common['release_year'],
                'engine_displacement' => $modification['engine_type'],
                'power' => $power,
                'center_bore' => (string)$centerBore,
                'pcd' => (string)$pcd,
                'lug_count' => $lugCount,
                'lug_size' => $lugSize,
                'lug_type' => $lugType,
                'market' => $modification['market']['abbr'],
                'years' => $modelData[$body]['years'],
//                'slug' => '', //bmw-7-series-f01-f02-f03-f04-restajling-activehybrid-7-n55b30a-2014
                'tires' => '',
                'wheels' => '',
                'sizes' => $sizes
            ];
        }

        return $modificationData;
    }

    public function prepareSizesData($data)
    {
        $sizes = [];

        foreach ($data as $size){

            $isMetric = $size['front']['tire_sizing_system'] === 'metric';
            $isRadial = $size['front']['tire_construction'] === 'R';
            $isNot82 = !$size['front']['tire_is_82series'];
            $diameter = isset($size['front']['rim_diameter']);

            if($isMetric && $isRadial && $isNot82 && $diameter){
                $rear = false;
                $is_rear = !$size['showing_fp_only'] && isset($size['rear']['rim_diameter']);
                $is_factory = $size['is_stock'];

                $front = [
                    'diameter' => $size['front']['rim_diameter'],
                    'tires' => [
                        'width' => $size['front']['tire_width'],
                        'height' => $size['front']['tire_aspect_ratio'],
                    ],
                    'wheels' => [
                        'width' => $size['front']['rim_width'],
                        'offset' => $size['front']['rim_offset'],
                    ]
                ];

                if ($is_rear) {
                    $rear = [
                        'diameter' => $size['rear']['rim_diameter'],
                        'tires' => [
                            'width' => $size['rear']['tire_width'],
                            'height' => $size['rear']['tire_aspect_ratio'],
                        ],
                        'wheels' => [
                            'width' => $size['rear']['rim_width'],
                            'offset' => $size['rear']['rim_offset'],
                        ]
                    ];
                }

                $sizes[] = [
                    'is_rear' => $is_rear,
                    'is_factory' => $is_factory,
                    'front' => $front,
                    'rear' => $rear,
                ];

            }
        }

        return $sizes;
    }


    public function import($data)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $this->brands = [];
            $this->models = [];

            $brandSlug = $data['brand']['slug'];
            $brand = self::getBrand($data['brand']);
            $brand_id = $brand->id;
            $release_year = $data['release_year'];
            $modelName = $data['model']['name'];
            $modifications = $data['modifications'];

            $commonParams = [
                'brand_id' => $brand_id,
                'model_name' => $modelName
            ];

            $modelData = $this->prepareModelData($commonParams, $modifications);
            $this->processModel($modelData);

            //return $data;

            $commonParams['release_year'] = $release_year;
            $modificationData = $this->prepareModificationData($commonParams, $modifications, $modelData);
            self::processModification($modificationData);

            $transaction->commit();

        } catch (Exception $e) {
            $transaction->rollBack();
            return $e->getMessage();
        }
        return true;
    }

//    public function import($data)
//    {
//        $transaction = Yii::$app->db->beginTransaction();
//        try {
//            $this->brands = [];
//            $this->models = [];
//
//            $this->processModifications($data);
//            $transaction->commit();
//        } catch (Exception $e) {
//            $transaction->rollBack();
//            return $e->getMessage();
//        }
//        return true;
//    }

    public function takeBigger($data = [])
    {
        $array = array_map(function ($var) {
            return (string)$var;
        }, $data);

        $finder = array_count_values($array);
        arsort($finder);

        return key($finder);
    }

}
