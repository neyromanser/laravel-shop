<?php

namespace Neyromanser\LaravelShop;

use Neyromanser\LaravelShop\Model\Order;
use Neyromanser\LaravelShop\Cart;

use DB;

class Shop{

    /**
     * Laravel application
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;
    static $cart = null;

    /**
     * Create a new confide instance.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    public function __construct($app, $cart){
        $this->app = $app;
        static::$cart = $cart;
    }

    static function Order(){
        return new Order();
    }

    static function Cart(){
        return static::$cart;
    }

    static function Wishlist(){
        $wishlist = static::$cart->instance('wishlist');
        return $wishlist;
    }

    static function getPayment($id=false){
        if($id)
            return DB::table('payment_method')->where('active',1)->where('id',$id)->select()->get();
        else
            return DB::table('payment_method')->where('active',1)->select()->get();
    }

    static function getShipping($id=false){
        if($id)
            return DB::table('shipping_method')->where('active',1)->where('id',$id)->select()->get();
        else
            return DB::table('shipping_method')->where('active',1)->select()->get();
    }
	
	static function getPaymentName($id){
        if($id){
            $item = DB::table('payment_method')->where('active',1)->where('id',$id)->select()->get();
			if($item && isset($item[0]))
				return $item[0]->name;
		}
		
		return ' - ';
    }
	
	static function getShippingName($id){
        if($id){
            $item = DB::table('shipping_method')->where('active',1)->where('id',$id)->select()->get();
			if($item && isset($item[0]))
				return $item[0]->name;
		}
		
		return ' - ';
    }
	

}
