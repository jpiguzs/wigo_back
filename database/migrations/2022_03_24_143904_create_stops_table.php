<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')
            ->references('id')
            ->on('origins');
            $table->foreignId('previous_city_id')->nullable()
            ->references('id')
            ->on('origins');
            $table->string('front_id');
            $table->double('total');
            $table->double('total_pick')->nullable();
            $table->double('total_stop')->nullable();
            $table->double('total_delivery')->nullable();
            $table->foreignId('budget_id')
            ->references('id')
            ->on('budgets');
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
        Schema::dropIfExists('stops');
    }
}
