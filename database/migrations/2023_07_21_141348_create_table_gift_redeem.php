<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableGiftRedeem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_redeem', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gift_id');
            $table->integer('qty');
            $table->integer('price_item');
            $table->integer('total');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('deleted_by')->nullable()->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gift_redeem');
    }
}
