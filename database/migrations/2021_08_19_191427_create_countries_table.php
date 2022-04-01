<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->integerIncrements('id')->unsigned();
            $table->string('name',255)->nullable(); 
            $table->char('code',10)->nullable();
            $table->char('currency_code',20)->nullable();
            $table->enum('status',['enable','disable','delete'])->default('enable');
            $table->unsignedSmallInteger('created_by');
            $table->unsignedSmallInteger('updated_by');
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
        Schema::dropIfExists('countries');
    }
}
