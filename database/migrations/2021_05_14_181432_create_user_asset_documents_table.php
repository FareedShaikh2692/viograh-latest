<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAssetDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_asset_documents', function (Blueprint $table) {
            $table->integerIncrements('id')->unsigned();
            $table->unsignedInteger('asset_id');
            $table->string('file',200);
            $table->dateTime('created_at')->useCurrent();            
            $table->unsignedInteger('created_ip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_asset_documents');
    }
}
