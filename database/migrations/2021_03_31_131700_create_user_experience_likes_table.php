<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserExperienceLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_experience_likes', function (Blueprint $table) {
            $table->integerIncrements('id')->unsigned();
            $table->unsignedInteger('feed_id');
            $table->unsignedInteger('user_id');
            $table->boolean('is_like')->comment('1=like, 0=dislike');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
            $table->unsignedInteger('created_ip')->nullable();
            $table->unsignedInteger('updated_ip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_experience_likes');
    }
}
