<?php

use Illuminate\Database\Seeder;

class Shipping_methodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('shipping_method')->insert([
            'name' => 'Самовывоз из магазина'
        ]);
        DB::table('shipping_method')->insert([
            'name' => 'Доставка нашим курьером'
        ]);
        DB::table('shipping_method')->insert([
            'name' => 'Доставка Укрпоштой'
        ]);
    }
}
