<?php

use Illuminate\Database\Seeder;

class Payment_methodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_method')->insert([
            'name' => 'Наличная'
        ]);
        DB::table('payment_method')->insert([
            'name' => 'Наложенный платеж'
        ]);
        DB::table('payment_method')->insert([
            'name' => 'Банковская карта'
        ]);
    }
}
