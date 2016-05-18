<?php

namespace Neyromanser\LaravelShop;

use Neyromanser\LaravelShop\Model\Order as Order;

class Shop{

    /**
     * Laravel application
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    /**
     * Create a new confide instance.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
       // static::$gatewayKey     = $this->getGateway();
    }

    public function Order(){
        return new Order();
    }

}
