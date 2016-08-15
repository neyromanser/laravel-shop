<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('state');
            $table->integer('items_number')->unsigned();
            $table->float('items_total', 15, 2);
            $table->integer('adjustments_numbers')->nullable();
            $table->float('adjustments_total', 15, 2)->nullable();
            $table->dateTime('completed_at')->nullable();
			$table->text('note')->nullable();
            $table->timestamps();

            $table->float('discount', 15, 2);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

			//custom fields
            $table->text('shipping_status')->nullable();
            $table->text('payment_status')->nullable();

            $table->integer('shipping_method_id')->unsigned();
            $table->foreign('shipping_method_id')->references('id')->on('shipping_method')->onDelete('cascade');

            $table->integer('payment_method_id')->unsigned();
            $table->foreign('payment_method_id')->references('id')->on('payment_method')->onDelete('cascade');

            $table->string('shipping_address')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_name')->nullable();
            $table->string('shipping_last_name')->nullable();
            $table->string('shipping_email')->nullable();
            $table->string('shipping_phone')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('orders');
	}

}
