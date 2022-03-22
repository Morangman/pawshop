<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('message_id')->index();
            $table->unsignedBigInteger('chat_id')->index();
            $table->string('url');
            $table->string('mime')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_files');
    }
}
