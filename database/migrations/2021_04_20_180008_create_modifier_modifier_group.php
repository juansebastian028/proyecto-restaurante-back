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
        Schema::create('modifiers_modifiers_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('modifier_id')->nullable();
            $table->unsignedBigInteger('modifier_group_id')->nullable();
            $table->foreign('modifier_id')->references('id')->on('modifiers');
            $table->foreign('modifier_group_id')->references('id')->on('modifier_groups');
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
        Schema::dropIfExists('modifiers_modifiers_groups');
    }
}
