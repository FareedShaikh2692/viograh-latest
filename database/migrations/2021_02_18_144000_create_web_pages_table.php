<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_pages', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('page_title',100);
            $table->string('page_heading',100);
            $table->text('page_content');
            $table->string('slug',100);
            $table->string('meta_tag',200);
            $table->string('meta_description',500);
            $table->enum('status',['enable','disable','delete'])->default('enable');
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned();
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
        Schema::dropIfExists('web_pages');
    }
}
