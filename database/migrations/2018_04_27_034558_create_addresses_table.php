<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',20)->comment('收货人名称');
            $table->integer('tel')->comment('收货人号码');
            $table->string('provence',20)->comment('省');
            $table->string('city',20)->comment('市');
            $table->string('area',20)->comment('区');
            $table->string('detail_address',30)->comment('详细地址');
            $table->integer('user_id')->comment('号主id');
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
        Schema::dropIfExists('addresses');
    }
}
