<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommoditiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commodities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description',30);
            $table->tinyInteger('is_selected');
            $table->string('name',10);
            $table->string('type_accumulation',10);
            $table->integer('goods_list');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('businessusers');
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
        Schema::dropIfExists('commodities');
    }
}
