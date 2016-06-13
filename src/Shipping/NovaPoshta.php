<?php namespace Neyromanser\LaravelShop\Shipping;

use Cache;

# todo: add cache for requests

class NovaPoshta{
    protected $key = 'no-key';
    protected $npApiName = 'nova_poshta';
    protected $url = 'https://api.novaposhta.ua/v2.0/json';
    protected $error = false;
    protected $cacheTime = 60 * 24;

    public function __construct($api_key){
        $this->key = $api_key;
    }

    public function getOptions(){
        return "data-address-name='Склад Новой Пошти: '";
    }

    public function apiStep($step, $request){
        $return = ['data'=>[], 'status'=>'error'];
        $parsedData = false;

        switch ($step){
            # getCities
            case 1 :
                $cacheKey = 'NP_Address_getCities';
                if(!($parsedData = $this->cacheGet($cacheKey))){
                    $rawResponse = $this->get('Address', 'getCities');
                    $return['next'] = 2;
                }
                break;

            # getWarehouses
            case 2 :
                $cacheKey = 'NP_AddressGeneral_getWarehouses_'.$request['city'];
                if(!($parsedData = $this->cacheGet($cacheKey))){
                    $rawResponse = $this->get('AddressGeneral', 'getWarehouses', ['CityName'=>$request['city']]);
                    $return['next'] = 2;
                }
                break;

            default : $this->error = 'wrong api step';
        }

        if($parsedData!==false){
            $return['status'] = 'success';
            $return['data'] = $parsedData;
            return $return;
        }

        $return['error'] = $this->error;
        if($this->error===false){
            $return['status'] = 'success';
            $return['data'] = $this->cachePut($cacheKey, $this->parseRaw($rawResponse, $step));
        }

        return $return;
    }

    private function cachePut($key, $data){
        Cache::store('file')->put($key, $data, $this->cacheTime);
        return $data;
    }

    private function cacheGet($key){
        if (Cache::store('file')->has($key)) {
            return Cache::store('file')->get($key);
        }
        return false;
    }

    private function parseRaw($rawResponse, $step){
        $return = [];
        switch ($step){
            # getCities
            case 1 :
                foreach($rawResponse['data'] as $item)
                    $return[] = ['name'=>$item['DescriptionRu'], 'id'=>$item['DescriptionRu']];
                    //$return[] = ['name'=>$item['DescriptionRu'], 'id'=>$item['CityID']];
                break;

            # getWarehouses
            case 2 :
                foreach($rawResponse['data'] as $item)
                    $return[] = ['name'=>$item['DescriptionRu'], 'id'=>$item['DescriptionRu']];
                    //$return[] = ['name'=>$item['DescriptionRu'], 'id'=>$item['CityID']];
                break;
        }

        return $return;
    }

    private function get($modelName, $calledMethod, $data=[]){
        $postData = [
            'apiKey'=>$this->key,
            'modelName'=>$modelName,
            'calledMethod'=>$calledMethod
        ];
        if($data) $postData['methodProperties']=$data;

        $ch = curl_init("{$this->url}/$modelName/$calledMethod");
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_HTTPAUTH => CURLAUTH_ANY,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode($postData)
        ));

        try{
            $response = curl_exec($ch);
            if($response === FALSE){
                $this->error = curl_error($ch);
            }else {
                $decoded = json_decode($response, TRUE);
                if(isset($decoded['success']) && $decoded['success']===false){
                    $this->error = implode(', ', $decoded['errors']);
                }
                return $decoded;
            }
        }catch (HttpException $ex){
            $this->error = $ex;
        }
    }
}

