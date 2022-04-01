<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('module', 50);
            $table->string('title', 100);
            $table->string('subject', 100);
            $table->string('from_email', 100);
            $table->string('to_email', 100)->comment('sent_mail_to_admin(0 / 1) ( 0 = to_email blank, 1=send mail to admin at to_email)');
            $table->tinyInteger('sent_mail_to_admin')->comment('0=send mail to user, 1= Send mail to Admin');
            $table->string('slug', 50);
            $table->string('from_text', 50);
            $table->string('comment', 250)->nullable();
            $table->mediumText('content');
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
        Schema::dropIfExists('mail_settings');
    }
}
