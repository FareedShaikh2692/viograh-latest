<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_feedback', function (Blueprint $table) {
            $table->integerIncrements('id')->unsigned();
            $table->unsignedInteger('user_id');
            $table->string('url',250)->nullable();
            $table->enum('type',['suggestion','issue','other'])->default('suggestion');
            $table->string('subject',250)->nullable();
            $table->text('message'); 
            $table->boolean('is_read')->default(0)->comment('1=read and 0=unread');
            $table->enum('status',['enable','disable','delete'])->default('enable');
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
        Schema::dropIfExists('user_feedback');
    }
}
