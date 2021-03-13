<?php

declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateStepsTable
 */
class CreateStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('steps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('name_id')->nullable();
            $table->string('slug')->nullable();
            $table->string('attribute')->nullable();
            $table->string('value');
            $table->text('decryption')->nullable();
            $table->timestamps();

            $table->foreign('name_id')
                ->references('id')
                ->on('step_names')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('steps');
    }
}
