<?php

namespace Neyromanser\LaravelShop\Model;


use Illuminate\Database\Eloquent\Model;
use App\ProductVariant;

class OrderItem extends Model{

    protected $table = 'orderItems';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function getProduct(){
        return ProductVariant::where('id',$this->line_item_id)->first();
        //return $this->belongsTo('App\ProductVariant', 'line_item_id', 'id');
    }

    public function variant(){
        return $this->belongsTo('App\ProductVariant', 'line_item_id');
    }
}
