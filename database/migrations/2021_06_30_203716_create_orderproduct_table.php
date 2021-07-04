<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderproductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderproduct', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('OrderID');
            $table->foreign('OrderID')->references('id')->on('order')->ondelete('cascade');

            $table->unsignedBigInteger('ProductID');
            $table->foreign('ProductID')->references('id')->on('product')->ondelete('cascade');

            $table->integer('ProductOrderQuentity');
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
        Schema::dropIfExists('orderproduct');
    }
}
