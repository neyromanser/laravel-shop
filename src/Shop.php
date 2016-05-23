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

    static function getPayment(){
        return DB::table('payment_method')->where('active',1)->select()->get();
    }

    static function getShipping(){
        return DB::table('shipping_method')->where('active',1)->select()->get();
    }

}
