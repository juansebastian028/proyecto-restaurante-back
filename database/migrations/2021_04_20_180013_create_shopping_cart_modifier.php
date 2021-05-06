<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppingCartModifier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_cart_modifier', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shopping_cart_id')->nullable();
            $table->foreign('shopping_cart_id')->references('id')->on('shopping_cart')->onDelete('set null');
            $table->unsignedBigInteger('order_product_id')->nullable();
            $table->foreign('order_product_id')->references('id')->on('order_product')->onDelete('set null');
            $table->unsignedBigInteger('modifier_id')->nullable();
            $table->foreign('modifier_id')->references('id')->on('modifiers')->onDelete('cascade');
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
