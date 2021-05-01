<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModifierModifierGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modifier_modifier_group', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('modifier_id')->nullable();
            $table->unsignedBigInteger('modifier_group_id')->nullable();
            $table->foreign('modifier_id')->references('id')->on('modifiers')->onDelete('cascade');
            $table->foreign('modifier_group_id')->references('id')->on('modifier_groups')->onDelete('cascade');
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
        Schema::dropIfExists('modifier_modifier_group');
    }
}
