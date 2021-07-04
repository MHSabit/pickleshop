<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreorderproductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preorderproduct', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('OrderID');
            $table->foreign('OrderID')->references('id')->on('preorder')->ondelete('cascade');

            $table->unsignedBigInteger('ProductID');
            $table->foreign('ProductID')->references('id')->on('product')->ondelete('cascade');
            

            $table->integer('ProductOrderQuentity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('preorderproduct');
    }
}
