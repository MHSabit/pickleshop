<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreorderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preorder', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('Customer_Name');
            $table->integer('Phone');
            $table->string('Address');
            $table->integer('price');
            $table->integer('discountammount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('preorder');
    }
}
