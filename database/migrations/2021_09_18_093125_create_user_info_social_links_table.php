<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInfoSocialLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_info_social_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_info_id');
            $table->string('name');
            $table->string('link');
            $table->timestamps();

            $table->foreign('user_info_id')->references('id')->on('user_infos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_info_social_links');
    }
}
