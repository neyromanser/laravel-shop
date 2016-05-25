

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
## Cart
```php
# Add to cart
Shop::Cart()->add([
    'id'      => $id,
    'name'    => $name,
    'qty'     => $request->input('qty', 1),
    'price'   => $item->price,
    'options' => $options
]);

# Remove from cart
Shop::Cart()->remove($id);

# Update cart
Shop::Cart()->update($id, $quantity);

# Cart total sum
Shop::Cart()->total()

# Cart positions amount
Shop::Cart()->count()

# Cart total products units
Shop::Cart()->count(false)

# Search in cart
Shop::Cart()->search(['id' => 123]);

# Associate cart with App\Model\Product
Shop::Cart()->associate('Product', 'App\\Model')

# New cart instance
Shop::Cart()->instance('wishlist')
```
## Order
```php
# Create order
Shop::Order()->order(Auth::user()->id, [
    'shipping_method_id' => $request->input('shipping_method',0),
    'payment_method_id' => $request->input('payment_method',0),
    'shipping_address' => $request->input('address',''),
    'shipping_city' => $request->input('city',''),
    'shipping_name' => $request->input('name',''),
    'shipping_email' => $request->input('email',''),
    'shipping_phone' => $request->input('phone',''),
    'note' => $request->input('note','')
]);

# Add items to order
Shop::Order()->addItems($order, [
    [
        "description" => $name,
        "currency" => $currency,
        "line_item_id" => $item->id,
        "line_item_type" => "App\\ProductVariant",
        "price" => $item->price,
        "quantity" => $item->qty,
        "vat" => 0
    ],[
        "description" => $name,
        "currency" => $currency,
        "line_item_id" => $item->id,
        "line_item_type" => "App\\ProductVariant",
        "price" => $item->price,
        "quantity" => $item->qty,
        "vat" => 0
    ],
    
]);
```


# Demo
```php
Shop::Cart()->instance('wishlist')->associate('Product', 'App')->add($addItem);
```

### based on
https://github.com/Trexology/laravel-order
https://github.com/Crinsane/LaravelShoppingcart

### look at
https://github.com/amsgames/laravel-shop