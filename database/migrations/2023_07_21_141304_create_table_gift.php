<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableGift extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->integer('category_id')->nullable()->unsigned();
            $table->string('picture', 255);
            $table->integer('price');
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
        Schema::dropIfExists('gift');
    }
}
