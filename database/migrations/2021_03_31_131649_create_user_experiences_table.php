<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_experiences', function (Blueprint $table) {
            $table->unsignedInteger('feed_id');
            $table->unsignedInteger('user_id');
            $table->string('title',200)->nullable();
            $table->string('file',200)->nullable();
            $table->longtext('description')->nullable();
            $table->date('date')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
            $table->unsignedInteger('created_ip')->nullable();
            $table->unsignedInteger('updated_ip')->nullable();
            $table->unique('feed_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_experiences');
    }
}
