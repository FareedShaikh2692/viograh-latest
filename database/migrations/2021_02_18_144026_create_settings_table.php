<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('setting_key');
            $table->text('value');
            $table->enum('setting_type',['general','email','not_seen'])->default('general');
            $table->text('comment')->nullable();
            $table->enum('status',['enable','disable','delete'])->default('enable');
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
            $table->integer('created_ip')->unsigned()->nullable();
            $table->integer('updated_ip')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
