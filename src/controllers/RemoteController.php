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
use GuzzleHttp;

class RemoteController extends Controller
{

    public function apiInstance($api, $query = [])
    {
        $module = Yii::$app->getModule('wsapi');
        $hash = $module->params['hash'];
        $baseUri = $module->params['baseUri'];
        $auth = ['hash' => $hash];
        $client = new GuzzleHttp\Client(['base_uri' => $baseUri]);
        $request = $client->request('POST', $api, [
            'query' => $query + $auth
        ]);
        return $request->getBody();
    }

    public function actionGetBrands()
    {
        $items = $this->apiInstance('get-brands');
        $brandList =  ArrayHelper::map($items, 'slug', 'name');

        return $brandList;

    }

    public function actionGetYears()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $brand = $parents[0];

                $query = ['brand' => $brand];
                $items = $this->apiInstance('get-years', $query);
                $items = json_decode($items, true);

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

                $query = [
                    'brand' => $brand,
                    'year' => $year
                    ];

                $items = $this->apiInstance('get-models', $query);
                $items = json_decode($items, true);

                return ['output' => $items, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    public function actionGetApiModifications()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $items = [];

        $brand = count($_GET) > 0 ? $_GET['brand'] : $_POST['brand'];
        $model = count($_GET) > 0 ? $_GET['model'] : $_POST['model'];
        $year = count($_GET) > 0 ? $_GET['year'] : $_POST['year'];

        $query = [
            'brand' => $brand,
            'model' => $model,
            'year' => $year
        ];

        $items = $this->apiInstance('get-modifications', $query);
        $items = json_decode($items, true);

//        return ArrayHelper::map(Yii::$app->wsapi->getApiModifications($brand, $model, $year), 'slug', 'name');
        return $items;
    }

    public function actionGetAutoSizes()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Yii::$app->request->post('model');
        $brand = Yii::$app->request->post('brand');
        $year = Yii::$app->request->post('year');
        $modification = isset($_POST['modification']) ? $_POST['modification'] : null;

        $query = [
            'brand' => $brand,
            'model' => $model,
            'year' => $year,
            'modification' => $modification
        ];

        $fullData = $this->apiInstance('get-sizes', $query);
        $fullData = json_decode($fullData, true);
        
//        $gens = (new AutoGenerator())->import($fullData);
        $query2 = [
            'brand' => $brand['slug'],
            'model' => $model['slug'],
            'year' => $year
        ];

        $items = $this->apiInstance('get-modifications', $query2);
        $items = json_decode($items, true);
        $gens = $items;
        
        return $this->renderPartial('../../widgets/views/_sizes', [
            'data' => $fullData,
            'gens' => $gens
        ]);
    }

}