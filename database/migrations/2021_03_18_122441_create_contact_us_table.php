<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactUsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_us', function (Blueprint $table) {
            $table->integerIncrements('id')->unsigned();
            $table->string('name',100);
            $table->string('email',200);
            $table->string('contact_number',20)->nullable();
            $table->string('subject',200);
            $table->string('message',500);
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
        Schema::dropIfExists('contact_us');
    }
}
