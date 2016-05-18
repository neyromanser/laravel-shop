<?php

namespace Neyromanser\LaravelShop;

use Neyromanser\LaravelShop\Model\Order;
use Neyromanser\LaravelShop\Cart;

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

}
