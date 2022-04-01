<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email', 100)->unique();
            $table->unsignedBigInteger('contact_number')->nullable();
            $table->string('profile_photo', 200)->nullable();
            $table->string('password', 500);
            $table->enum('admin_type', ['super_admin', 'sub_admin'])->default('sub_admin');
            $table->enum('status', ['enable', 'disable', 'delete'])->default('enable');
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
        Schema::dropIfExists('manages');
    }
}
