<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('order_code')->unique();
            $table->unsignedTinyInteger('order_status')->default(0);
            $table->unsignedInteger('shop_id');
            $table->string('shop_name');
            $table->string('shop_img');
            $table->decimal('order_price',11,2);
            $table->string('receipt_name',20)->default('');
            $table->string('receipt_tel',20)->default('');
            $table->string('receipt_provence',20)->default('');
            $table->string('receipt_city',20)->default('');
            $table->string('receipt_area',20)->default('');
            $table->string('receipt_detail_address',50)->default('');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
