<?php

namespace Neyromanser\LaravelShop\Model;

use Illuminate\Database\Eloquent\Model;
use Neyromanser\LaravelShop\Shipping\NovaPoshta;

class Shipping_method extends Model
{
    
    protected $table = 'shipping_method';

    protected $fillable = ['name','api_key','api'];

    protected $hidden = [ 'created_at', 'updated_at'];

    protected $methodApi = false;

    private function hasApi(){
        if($this->api && $this->api=='nova_poshta'){
            $this->methodApi = new NovaPoshta($this->api_key);
            return true;
        }
    }

    public function apiOptions(){
        if($this->hasApi()){
            return $this->methodApi->getOptions();
        }
    }
    
    public function apiStep($step, $request){
        if($this->hasApi()){
            return $this->methodApi->apiStep($step, $request);
        }
    }

}
