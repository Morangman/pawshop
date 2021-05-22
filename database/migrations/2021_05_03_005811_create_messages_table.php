<?php

declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateMessagesTable
 */
class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('chat_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('text')->nullable();
            $table->text('simple_text')->nullable();
            $table->tinyInteger('sender')->unsigned()->nullable();
            $table->tinyInteger('viewed')->unsigned()->default(1);
            $table->timestamp('time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
}
