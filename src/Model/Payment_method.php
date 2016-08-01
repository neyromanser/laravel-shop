<?php

namespace Neyromanser\LaravelShop\Model;

use Illuminate\Database\Eloquent\Model;

class Payment_method extends Model
{
    protected $table = 'payment_method';

    protected $fillable = ['name', 'gateway', 'private_key','public_key', 'css_show', 'css_hide'];

    protected $hidden = [ 'created_at', 'updated_at' ];
    
}
