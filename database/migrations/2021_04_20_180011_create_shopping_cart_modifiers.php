<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppingCartModifiers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_cart_modifiers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shopping_cart_id')->nullable();
            $table->unsignedBigInteger('modifiers_id')->nullable();
            $table->foreign('shopping_cart_id')->references('id')->on('shopping_cart');
            $table->foreign('modifiers_id')->references('id')->on('modifiers');
            $table->integer('unit_price_modifier');
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
        Schema::dropIfExists('shopping_cart_modifiers');
    }
}
