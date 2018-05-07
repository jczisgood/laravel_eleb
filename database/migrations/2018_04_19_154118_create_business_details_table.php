<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shop_name');
            $table->string('shop_img');
            $table->tinyInteger('shop_rating');
            $table->tinyInteger('brand');
            $table->tinyInteger('on_time');
            $table->tinyInteger('fengniao');
            $table->tinyInteger('bao');
            $table->tinyInteger('piao');
            $table->tinyInteger('zhun');
            $table->decimal('start_send');
            $table->decimal('send_cost');
            $table->integer('distance');
            $table->integer('estimate_time');
            $table->string('notice');
            $table->string('discount');
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
        Schema::dropIfExists('business_details');
    }
}
