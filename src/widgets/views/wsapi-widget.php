<?php



//use yii;
use kartik\depdrop\DepDrop;
//use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\select2\Select2;

//$items = ArrayHelper::map($model->getApiBrands(), 'slug', 'name');

//$proc = (new \taroxx\wsapi\models\AutoGenerator)->brandProcess($items);

$items = ArrayHelper::map(\taroxx\wsapi\models\AutoBrand::find()->asArray()->all(), 'slug','name');

//var_dump($items);
//$items = [
//        'acura' => 'Acura',
//        'bmw' => 'BMW',
//        'honda' => 'Honda',
//        'toyota' => 'Toyota',
//        'alpine' => 'Alpine'
//];

?>

<div class="test-div">test widget</div>

<?php
echo \yii\helpers\Html::dropDownList(
        'brands',
        false,
        $items,
        [
            'id' => 'auto-brand'
        ]
);
?>

<?php
//echo Select2::widget([
//    'data' => [
//        'acura' => 'Acura',
//        'bmw' => 'BMW',
//        'honda' => 'Honda'
//    ],
//    'name' => 'selector[l1]',
////    'id' => 'auto-brand',
//    'options' => [
//        'id' => 'auto-brand',
////        'placeholder' => 'Select a state ...'
//    ],
//    'pluginOptions' => [
//        'allowClear' => true
//    ],
//    'pluginEvents' => [
//        "change" => "function(e) {
////            console.log(e);
//
//        }",
//    ]
//])
; ?>
<div>
<?=  DepDrop::widget([
        'name' => 'year',
//        'type' => DepDrop::TYPE_SELECT2,
        'options' => [
            'id'=>'auto-year',
            'prompt' => 'Выберите производителя',
        ],
//        'select2Options' => ['pluginOptions' => ['allowClear' => true]],
        'pluginOptions' => [
            'depends' => ['auto-brand'],
            'placeholder' => 'Выберите ...',
            'loadingText' => '...',
            'url' => Url::to(['/wsapi/remote/get-years'])
        ]
    ]
); ?>
</div>

<div>
<?=  DepDrop::widget([
    'name' => 'model',
//    'type' => DepDrop::TYPE_SELECT2,
    'options' => [
            'id'=>'auto-model',
            'prompt' => 'Выберите год',
        ],
//    'select2Options' => ['pluginOptions' => ['allowClear' => true]],
    'pluginOptions' => [
        'depends' => [
            'auto-year',
            'auto-brand'
        ],
        'initDepends' => ['auto-brand'],
        'placeholder' => 'Выберите ...',
        'loadingText' => '...',
        'url' => Url::to(['/wsapi/remote/get-models'])
        ],
    ]
); ?>
</div>

<div class="auto__sizes-container"></div>

<?php
$urlTo = \yii\helpers\Url::toRoute(['/wsapi/remote/get-auto-sizes']);
$this->registerJs(<<<JS
     
     $('#auto-model').on('change', function(e) {
         let brand = $('#auto-brand option:selected');
         let year = $('#auto-year');
         console.log($(this));
            $.post(
                '$urlTo',
                {
                    'brand': { slug: brand.val(), name: brand.text()},
                    'year': year.val(),
                    'model': {slug: this.value, name: this.options[this.selectedIndex].text}
                },
                function(data) {
                      console.log('data');
                      // console.log(data);
                      $('.auto__sizes-container').html(data);
                }
            );
      });
     
JS
) ?>
