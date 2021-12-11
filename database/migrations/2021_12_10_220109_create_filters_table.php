<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id');
            $table->string('status')->nullable();
            $table->string('tiers')->nullable();
            $table->integer('this_week')->nullable();
            $table->integer('last_week')->nullable();
            $table->integer('this_month')->nullable();
            $table->integer('last_month')->nullable();
            $table->date('fdate')->nullable();
            $table->date('tdate')->nullable();
            $table->timestamps();

            $table->foreign('creator_id')->references('id')->on('creators')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filters');
    }
}
