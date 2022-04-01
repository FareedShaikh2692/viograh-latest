<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integerIncrements('id')->unsigned();
            $table->string('first_name',100);
            $table->string('last_name',100);
            $table->string('profile_image',200)->nullable();
            $table->string('email',200);
            $table->string('google_id',30)->nullable();
            $table->string('password',500)->nullable();
            $table->enum('login_platform',['native','google','other']);
            $table->json('tree');
            $table->enum('gender',['male','female','other'])->nullable();
            $table->string('phone_number',15)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('dial_code',7)->nullable();
            $table->text('biography')->nullable();            
            $table->string('place_of_birth',200)->nullable();            
            $table->string('favourite_movie',400)->nullable();
            $table->string('favourite_song',400)->nullable();
            $table->string('favourite_book',400)->nullable();
            $table->string('favourite_eating_joints',400)->nullable();
            $table->string('hobbies',400)->nullable();
            $table->string('food',400)->nullable();
            $table->string('role_model',200)->nullable();
            $table->string('car',200)->nullable();
            $table->string('brand',200)->nullable();
            $table->string('tv_shows',400)->nullable();
            $table->string('actors',200)->nullable();
            $table->string('sports_person',200)->nullable();
            $table->string('politician',200)->nullable();
            $table->string('diet',400)->nullable();
            $table->string('zodiac_sign',200)->nullable();

            $table->string('blood_group',22)->nullable();
            $table->string('places_lived',100)->nullable();
            $table->string('profession',200)->nullable();
            $table->string('address',400)->nullable();
            $table->text('about_me')->nullable();
            $table->enum('profile_privacy',['public','family','private'])->default('public');
            $table->boolean('email_notification')->default(1);
            $table->string('banner_image',200)->nullable();
            $table->string('essence_of_life',200)->nullable();
            $table->string('mynetworth_verification_code',10)->nullable();
            $table->string('remember_token',255)->nullable()->default(null);
            $table->smallInteger('currency_id')->default(5);
            $table->enum('status',['pending','enable','disable','delete'])->default('pending');
            $table->unsignedInteger('created_ip')->nullable();
            $table->unsignedInteger('updated_ip')->nullable();
            $table->index('email');
            $table->index('phone_number');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
