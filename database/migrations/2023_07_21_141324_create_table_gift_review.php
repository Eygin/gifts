<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableGiftReview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_review', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gift_id');
            $table->integer('rating');
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
        Schema::dropIfExists('gift_review');
    }
}
