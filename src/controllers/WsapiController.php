<?php


namespace taroxx\wsapi\controllers;

use taroxx\wsapi\components\WheelSizeAPI;
use taroxx\wsapi\models\AutoBrand;
use taroxx\wsapi\models\AutoGenerator;
use taroxx\wsapi\models\Brand;
use taroxx\wsapi\models\Search;
use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class WsapiController extends Controller
{

    public function actionGetYears()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $brand = $parents[0];
                $items = [
                    ['id' => '1964', 'name' => '1964'],
                    ['id' => '1991', 'name' => '1991'],
                    ['id' => '1995', 'name' => '1995'],
                    ['id' => '1998', 'name' => '1998'],
                    ['id' => '2000', 'name' => '2000'],
                    ['id' => '2002', 'name' => '2002'],
                    ['id' => '2005', 'name' => '2005'],
                    ['id' => '2008', 'name' => '2008'],
                    ['id' => '2011', 'name' => '2011'],
                    ['id' => '2014', 'name' => '2014'],
                    ['id' => '2017', 'name' => '2017'],
                    ['id' => '2019', 'name' => '2019'],
                ];

//                $items = Yii::$app->wsapi->getYearList($brand);

                return ['output' => $items, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    public function actionGetModels()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $items = [];
        if (isset($_POST['depdrop_parents'])) {
            $ids = $_POST['depdrop_parents'];
            $year = empty($ids[0]) ? null : $ids[0];
            $brand = empty($ids[1]) ? null : $ids[1];
            if ($year != null && $year != '...') {
                $items = [
                    ['id' => 'rsx', 'name' => 'RSX'],
                    ['id' => 'rlx', 'name' => 'rlx'],
                    ['id' => 'x6', 'name' => 'X6'],
                    ['id' => 'x7', 'name' => 'X7'],
                    ['id' => 'civic', 'name' => 'Civic'],
                    ['id' => 'land-cruiser', 'name' => 'Land Cruiser'],
                    ['id' => 'land-cruiser-prado', 'name' => 'Land Cruiser Prado'],
                    ['id' => 'a108', 'name' => 'A108'],

                ];


//                $items = Yii::$app->wsapi->getModelList($brand, $year);

//                array_walk($items, function($brand) use ($brand)
//                {
//                    if($brand['id'] === $brand) echo $brand['name'];
//                });

                return ['output' => $items, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    public function actionGetAutoSizes()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Yii::$app->request->post('model');
        $brand = Yii::$app->request->post('brand');
        $year = Yii::$app->request->post('year');
        $modification = isset($_POST['modification']) ? $_POST['modification'] : null;
        
        $sizesData = Yii::$app->wsapi->getApiSizes($brand['slug'], $model['slug'], $year, $modification);
        
        $commonData = [
            'brand' => ['slug' => $brand['slug'], 'name' => $brand['name']],
            'release_year' => $year,
            'model' => ['slug' => $model['slug'], 'name' => $model['name']]
        ];

        $fullData = ArrayHelper::merge($commonData, ['modifications' => $sizesData]);

        $gens = (new AutoGenerator())->import($fullData);

        return $this->renderPartial('../../widgets/views/_sizes', [
            'data' => $fullData,
            'gens' => '123'
        ]);
    }

}