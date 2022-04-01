<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserForgotPasswordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_forgot_passwords', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->string('token',50);
            $table->timestamp('created_at')->nullable()->useCurrent();
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
        Schema::dropIfExists('user_forgot_passwords');
    }
}
