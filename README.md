

# laravel-shop
Shop module for Laravel 5
Include Cart and Order

# Installation

    composer require "neyromanser/laravel-shop:1.*"

After installation，go to `config/app.php` under `providers` section to add the following:

    Neyromanser\LaravelShop\LaravelShopServiceProvider::class

and under "aliases" add:

    'Shop' => Neyromanser\LaravelShop\Facades\ShopFacade::class


publish the migration and config files with the command:

    php artisan vendor:publish

Edit additional settings at `config/shop.php`

```php
    return [
        'currency' => "грн."
    ];
```

# Usage

