<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_assets', function (Blueprint $table) {
            $table->integerIncrements('id')->unsigned();
            $table->unsignedInteger('user_id');
            $table->string('title',100)->nullable();
            $table->string('amount',150)->nullable();
            $table->longtext('description')->nullable();
            $table->string('nominee_name',100)->nullable();
            $table->string('nominee_email',200)->nullable();
            $table->string('nominee_phone_number',15)->nullable();
            $table->boolean('is_save_as_draft')->default('0');
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
        Schema::dropIfExists('user_assets');
    }
}
