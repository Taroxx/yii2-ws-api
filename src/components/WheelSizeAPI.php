<?php

namespace taroxx\wsapi\components;

use WsApiClient;
//use mikefinch\YandexKassaAPI\interfaces\OrderInterface;
use yii\base\Component;
use yii\di\Instance;
use WsApiClient\Api\CountriesApi;
use WsApiClient\Api\BoltPatternsApi;
use WsApiClient\Api\MakesApi;
use WsApiClient\Api\MarketsApi;
use WsApiClient\Api\YearsApi;
use WsApiClient\Api\ModelsApi;
use WsApiClient\Api\TrimsApi;
use WsApiClient\Api\SearchApi;

class WheelSizeAPI extends Component
{

    public $key;
    private $config;

    public $brand;
    public $queryParams;

    CONST API_COUNTRY = 'WsApiClient\Api\CountriesApi';
    CONST API_BOLT_PATTERNS = 'WsApiClient\Api\BoltPatternsApi';
    CONST API_BRANDS = 'WsApiClient\Api\MakesApi';
    CONST API_MARKET = 'WsApiClient\Api\MarketsApi';
    CONST API_YEAR = 'WsApiClient\Api\YearsApi';
    CONST API_MODEL = 'WsApiClient\Api\ModelsApi';
    CONST API_MODIFICATION = 'WsApiClient\Api\TrimsApi';
    CONST API_SEARCH = 'WsApiClient\Api\SearchApi';

    public function init()
    {
        parent::init();
//        $request = \Yii::$app->request;
//        $this->queryParams = $request->queryParams;
        $this->config = WsApiClient\Configuration::getDefaultConfiguration()->setApiKey('user_key', $this->key);
    }


    public function apiInstance($api)
    {
        $apiInstance = new $api(
            new \GuzzleHttp\Client(),
            $this->config
        );
        return $apiInstance;
    }

    public function getApiCountries()
    {
        /**
         * @var $result WsApiClient\Model\Country[]
         * @var $apiInstance CountriesApi
         */
        $apiInstance = $this->apiInstance(self::API_COUNTRY);

        try {
            $list = $apiInstance->countriesList();
        } catch (Exception $e) {
            echo 'Exception when calling CountriesApi->countriesList: ', $e->getMessage(), PHP_EOL;
        }

        $result = [];
        foreach ($list as $country){
            $result[] = json_decode($country, true);
        }

        return $result;

    }

    public function getApiMarkets()
    {
        /**
         * @var $result WsApiClient\Model\Market[]
         * @var $apiInstance MarketsApi
         */
        $apiInstance = $this->apiInstance(self::API_MARKET);

        try {
            $list = $apiInstance->marketsList();
        } catch (Exception $e) {
            echo 'Exception when calling MarketsApi->marketsList: ', $e->getMessage(), PHP_EOL;
        }

        $result = [];
        foreach ($list as $market){
            $result[] = json_decode($market, true);
        }

        return $result;

    }

    public function getApiBoltPatterns()
    {
        /**
         * @var $result WsApiClient\Model\BoltPattern[]
         * @var $apiInstance BoltPatternsApi
         */
        $apiInstance = $this->apiInstance(self::API_BOLT_PATTERNS);

        try {
            $list = $apiInstance->boltPatternsList();
        } catch (Exception $e) {
            echo 'Exception when calling BoltPatternsApi->boltPatternsList: ', $e->getMessage(), PHP_EOL;
        }
        
        $result = [];
        foreach ($list as $pattern){
            $result[] = json_decode($pattern, true);
        }

        return $result;
    }

    /**
     * @var $bolt_patterns array
     * @result array
     */
    public function getApiModificationsByBoltPattern($bolt_pattern = null)
    {
        /**
         * @var $list WsApiClient\Model\MakeWithModels[]
         * @var $apiInstance BoltPatternsApi
         */
        $apiInstance = $this->apiInstance(self::API_BOLT_PATTERNS);

        if (!isset($bolt_pattern)) return false;

        try {
            $list = $apiInstance->boltPatternsRead($bolt_pattern);
        } catch (Exception $e) {
            echo 'Exception when calling BoltPatternsApi->boltPatternsRead: ', $e->getMessage(), PHP_EOL;
        }

        $result = [];

        foreach ($list as $brand) {
            /**
             * @var $models WsApiClient\Model\ModelWithTrims[]
            */
            $brandName = $brand->getMake()->getNameEn();
            $models = $brand->getModels();
            $result[$brandName] = json_decode($brand, true);
        }

        return $result;
    }

    public function getApiBrands($lang = null)
    {
        /**
         * @var $result WsApiClient\Model\Make[]
         * @var $apiInstance MakesApi
         */
        $apiInstance = $this->apiInstance(self::API_BRANDS);

        try {
            $list = $apiInstance->makesList($lang);
        } catch (Exception $e) {
            echo 'Exception when calling MakesApi->makesList: ', $e->getMessage(), PHP_EOL;
        }

        $result = [];
        foreach ($list as $brand){
            $result[] = json_decode($brand, true);
        }

        return $result;
    }

    public function getBrandList($lang = null)
    {

        $brandList = array_map(function($b){ return $b['name']; }, $this->getApiBrands($lang));
        return $brandList;

    }

    public function getApiYears($brand, $model = null)
    {
        /**
         * @var $result WsApiClient\Model\Year[]
         * @var $apiInstance YearsApi
         */
        $apiInstance = $this->apiInstance(self::API_YEAR);

        try {
            $list = $apiInstance->yearsList($brand, $model);
        } catch (Exception $e) {
            echo 'Exception when calling YearsApi->yearsList: ', $e->getMessage(), PHP_EOL;
        }

        $result = [];
        foreach ($list as $year){
            $result[] = json_decode($year, true);
        }

        return $result;
    }
    public function getYearList($brand, $model = null)
    {

        $yearList = array_map(function($b){

            $result = [
                'id' => $b['slug'],
                'name' => $b['name']
            ];

            return $result;

        }, $this->getApiYears($brand, $model));
        return $yearList;
        
    }

    public function getApiModels($brand, $year = null, $lang = null)
    {
        /**
         * @var $result WsApiClient\Model\Model[]
         * @var $apiInstance ModelsApi
         */
        $apiInstance = $this->apiInstance(self::API_MODEL);

        try {
            $list = $apiInstance->modelsList($brand, $year, $lang);
        } catch (Exception $e) {
            echo 'Exception when calling ModelsApi->modelsList: ', $e->getMessage(), PHP_EOL;
        }

        $result = [];
        foreach ($list as $model){
            $result[] = json_decode($model, true);
        }

        return $result;
    }

    public function getModelList($brand, $year = null, $lang = null)
    {

        $modelList = array_map(function($b){

            $result = [
                'id' => $b['slug'],
                'name' => $b['name']
            ];

            return $result;

            }, $this->getApiModels($brand, $year, $lang));

        return $modelList;

    }

//    public function getApiModelInfo($brand, $model_slug)
//    {
//        /**
//         * @var $result \WsApiClient\Model\ModelWithTires
//         * @var $apiInstance ModelsApi
//         */
//        $apiInstance = $this->apiInstance(self::API_MODEL);
//
//        try {
//            $list = $apiInstance->modelsRead($brand, $model_slug);
//        } catch (Exception $e) {
//            echo 'Exception when calling ModelsApi->modelsRead: ', $e->getMessage(), PHP_EOL;
//        }
//
//        $result = [];
//        foreach ($list as $model){
//            $result[] = json_decode($model, true);
//        }
//
//        return $result;
//    }

    public function getApiModifications($brand, $model, $year)
    {
        /**
         * @var $result WsApiClient\Model\Trim[]
         * @var $apiInstance TrimsApi
         */
        $apiInstance = $this->apiInstance(self::API_MODIFICATION);

        try {
            $list = $apiInstance->trimsList($brand, $model, $year);
        } catch (Exception $e) {
            echo 'Exception when calling TrimsApi->trimsList: ', $e->getMessage(), PHP_EOL;
        }

        $result = [];
        foreach ($list as $modification){
            $result[] = json_decode($modification, true);
        }

        return $result;
    }

    public function getApiSizes($brand, $model, $year, $modification = null)
    {
        /**
         * @var $result WsApiClient\Model\Vehicle[]
         * @var $apiInstance SearchApi
         */
        $apiInstance = $this->apiInstance(self::API_SEARCH);

        try {
            $list = $apiInstance->searchByModelList($brand, $model, $year, $modification);
        } catch (Exception $e) {
            echo 'Exception when calling SearchApi->searchByModelList: ', $e->getMessage(), PHP_EOL;
        }

        $result = [];
        foreach ($list as $size){
            $result[] = json_decode($size, true);
        }

        return $result;
    }


}
