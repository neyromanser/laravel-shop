<?php

namespace Neyromanser\LaravelShop;
/**
 * Created by PhpStorm.
 * User: ray
 * Date: 15/7/27
 * Time: 上午11:19
 */

use Illuminate\Support\ServiceProvider;

class LaravelShopServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAssets();
        $this->registerServices();
    }

    public function registerAssets()
    {
        $this->publishes([
            __DIR__.'/../migrations/' => database_path('/migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__.'/config/config.php' => config_path('shop.php')
        ], 'config');
    }

    protected function registerServices() {
      $this->app->bind('shop', function ($app) {
        return new Shop($app);
      });

      $this->mergeConfigFrom(__DIR__ . '/config/config.php', 'shop');
    }

    /**
     * Helper to get the config values
     *
     * @param  string $key
     * @return string
     */
    protected function config($key, $default = null)
    {
        return config("shop.$key", $default);
    }

    /**
     * Get an instantiable configuration instance. Pinched from dingo/api :)
     *
     * @param  mixed  $instance
     * @return object
     */
    protected function getConfigInstance($instance)
    {
        if (is_callable($instance)) {
            return call_user_func($instance, $this->app);
        } elseif (is_string($instance)) {
            return $this->app->make($instance);
        }

        return $instance;
    }
}
