<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_feeds', function (Blueprint $table) {
            $table->integerIncrements('id')->unsigned();
            $table->unsignedInteger('user_id');
            $table->tinyInteger('type_id')->unsigned();
            $table->mediumInteger('like_count')->default(0)->unsigned();
            $table->mediumInteger('comment_count')->default(0)->unsigned();
            $table->enum('privacy',['public','family','private'])->default('public');
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
        Schema::dropIfExists('user_feeds');
    }
}
