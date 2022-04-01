<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserExperienceCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_experience_comments', function (Blueprint $table) {
            $table->integerIncrements('id')->unsigned();
            $table->unsignedInteger('feed_id');
            $table->unsignedInteger('user_id');
            $table->string('comment',500);
            $table->dateTime('created_at')->useCurrent();
            $table->unsignedInteger('created_ip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_experience_comments');
    }
}
