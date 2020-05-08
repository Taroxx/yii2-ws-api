<?php

use yii\helpers\Html;



?>

    <div><?= var_dump($gens) ?></div>

<?php
    echo yii\widgets\DetailView::widget([
        'model' => 'yii\base\Model',
        'options' => [
            'class' => 'product-stats',
            'tag' => 'div',
        ],
        'template' => '<div class="product-stats__row"><div class="product-stats__name">{label}</div><div class="product-stats__value">{value}</div></div>',
        'attributes' => [
            [
                'label' => 'Производитель',
                'value' => $data['brand']['name']
            ],
            [
                'label' => 'Модель',
                'value' => $data['model']['name']
            ],
            [
                'label' => 'Дата производства',
                'value' => $data['release_year']
            ]
        ]
    ]);
?>

<?php if (!0): ?>
    <?php foreach ($data['modifications'] as $modificationParams): ?>

        <?php
        //common
        $modMarketName = $modificationParams['market']['slug'];
        $modMarketFullName = $modificationParams['market']['name_en'];
        $modName = $modificationParams['trim'];
        $modSlug = $modificationParams['slug'];
        $modGeneration = $modificationParams['generation']; //array
        $modGenerationName = $modGeneration['name'];
        $modGenerationStartYear = $modGeneration['start_year'];
        $modGenerationEndYear = $modGeneration['end_year'];
        $modGenerationTitle = $modGeneration['bodies'][0]['title']; // TODO: тут нужен ич
        $modGenerationImage = $modGeneration['bodies'][0]['image']; // TODO: и тут
        $modStudHoles = $modificationParams['stud_holes'];
        $modPCD = $modificationParams['pcd'];
        $modCenterBore = $modificationParams['centre_bore'];
        $modLockType = $modificationParams['lock_type'];
        $modLockText = $modificationParams['lock_text'];
        $modBoltPattern = $modificationParams['bolt_pattern'];
        $modPower = $modificationParams['power']; //array
        $modEngine = $modificationParams['engine_type'];
        $modFuel = $modificationParams['fuel'];

        echo yii\widgets\DetailView::widget([
            'model' => 'yii\base\Model',
            'options' => [
                'class' => 'product-stats',
                'tag' => 'div',
            ],
            'template' => '<div class="product-stats__row"><div class="product-stats__name">{label}</div><div class="product-stats__value">{value}</div></div>',
            'attributes' => [
                [
                    'label' => 'Заголовок',
                    'value' => $modGenerationTitle,
//                    'format' => 'raw',
                ],
                [
                    'label' => 'Фото',
                    'value' => Html::img($modGenerationImage),
                    'format' => 'raw',
                ],
                [
                    'label' => 'Рынок распространения',
                    'value' => Html::tag('div', $modMarketName . '(' . $modMarketFullName . ')', ['class' => 'modification-market']),
                    'format' => 'raw'
                ],
                [
                    'label' => 'Название',
                    'value' => $modName,
//                    'format' => 'raw',
                ],
                [
                    'label' => 'Кузов',
                    'value' => $modGenerationName,
//                    'format' => 'raw',
                ],
                [
                    'label' => 'Начало производства',
                    'value' => $modGenerationStartYear,
//                    'format' => 'raw',
                ],
                [
                    'label' => 'Конец производства',
                    'value' => $modGenerationEndYear,
//                    'format' => 'raw',
                ],
                [
                    'label' => 'PCD',
                    'value' => $modBoltPattern,
//                    'format' => 'raw',
                ],
                [
                    'label' => 'ЦО',
                    'value' => $modCenterBore,
//                    'format' => 'raw',
                ],
                [
                    'label' => 'Крепеж',
                    'value' => $modLockType . ' ' . $modLockText,
//                    'format' => 'raw',
                ],
                [
                    'label' => 'Тип двигателя',
                    'value' => $modEngine,
//                    'format' => 'raw',
                ],
            ]
        ]);

        //sizes
        $modSizes = $modificationParams['wheels']; //array
        foreach ($modSizes as $sizes) {

            $isStock = $sizes['is_stock'];
            $front = $sizes['front'];
            $isMetric = $front['tire_sizing_system'] === 'metric';
            $isRadial = $front['tire_construction'] === 'R';
            $isNot82 = !$front['tire_is_82series'];
            $diameter = isset($front['rim_diameter']);

            if ($isMetric && $isRadial && $isNot82 && $diameter)
            {
                $showFpOnly = !$sizes['showing_fp_only'] && isset($sizes['rear']['rim_diameter']);

                //tire
                $frontTireTitle = $front['tire'];
                $frontTireDiameter = $front['rim_diameter'];
                $frontTireWidth = isset($front['tire_width']) ? $front['tire_width'] : false;
                $frontTireHeight = isset($front['tire_aspect_ratio']) ? $front['tire_aspect_ratio'] : false;
                $frontTireLoadIndex = isset($front['load_index']) ? $front['load_index'] : false;
                $frontTireSpeedIndex = isset($front['speed_index']) ? $front['speed_index'] : false;

                //wheel
                $frontWheelTitle = $front['rim'];
                $frontWheelDiameter = $front['rim_diameter'];
                $frontWheelWidth = $front['rim_width'];
                $frontWheelOffset = $front['rim_offset'];

                $rear = false;

                $rearTireTitle = '';
                $rearTireLoadIndex = '';
                $rearTireSpeedIndex = '';
                $rearWheelTitle = '';

                if ($showFpOnly) {
                    $rear = $sizes['rear'];

                    //tire
                    $rearTireTitle = $front['tire'];
                    $rearTireDiameter = isset($rear['rim_diameter']) ? $rear['rim_diameter'] : false;
                    $rearTireWidth = isset($rear['tire_width']) ? $rear['tire_width'] : false;
                    $rearTireHeight = isset($rear['tire_aspect_ratio']) ? $rear['tire_aspect_ratio'] : false;
                    $rearTireLoadIndex = isset($rear['load_index']) ? $rear['load_index'] : false;
                    $rearTireSpeedIndex = isset($rear['speed_index']) ? $rear['speed_index'] : false;

                    //wheel
                    $rearWheelTitle = $front['rim'];
                    $rearWheelDiameter = isset($rear['rim_diameter']) ? $rear['rim_diameter'] : false;
                    $rearWheelWidth = $rear['rim_width'];
                    $rearWheelOffset = $rear['rim_offset'];

                }

                echo yii\widgets\DetailView::widget([
                    'model' => 'yii\base\Model',
                    'options' => [
                        'class' => 'product-stats',
                        'tag' => 'div',
                    ],
                    'template' => '<div class="product-stats__row"><div class="product-stats__name">{label}</div><div class="product-stats__value">{value}</div></div>',
                    'attributes' => [
                        [
                            'label' => 'Шины (передняя ось / обе оси)',
                            'value' => $frontTireTitle . ' ' . $frontTireSpeedIndex . ' ' . $frontTireLoadIndex,
//                        'format' => 'raw',
                        ],
                        [
                            'label' => 'Шины (Задняя Ось)',
                            'value' => $rearTireTitle . ' ' . $rearTireSpeedIndex . ' ' . $rearTireLoadIndex,
                            'visible' => $showFpOnly,
                        ],
                        [
                            'label' => 'Диски (передняя ось / обе оси)',
                            'value' => $frontWheelTitle,
//                        'format' => 'raw',
                        ],
                        [
                            'label' => 'Диски (Задняя Ось)',
                            'value' => $rearWheelTitle,
                            'visible' => $showFpOnly,
                        ],
                    ]
                ]);
            }

        }
        ?>

    <?php endforeach; ?>
<?php endif; ?>