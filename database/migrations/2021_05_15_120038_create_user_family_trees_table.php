<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFamilyTreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_family_trees', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('family_tree_id');
            $table->string('image',200)->nullable();
            $table->string('name',200);
            $table->string('email',200)->nullable();
            $table->string('age',10);
            $table->enum('gender', ['male', 'female']);
            $table->string('phone_number',20)->nullable();
            $table->boolean('is_alive')->default(0)->comment('1=dead and 0=alive');
            $table->tinyInteger('relationship');
            $table->enum('request_status', ['pending', 'accept', 'reject'])->default('pending');
            $table->string('token',250)->nullable();
            $table->enum('status', ['enable', 'disable', 'delete'])->default('enable');
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
        Schema::dropIfExists('user_family_trees');
    }
}
