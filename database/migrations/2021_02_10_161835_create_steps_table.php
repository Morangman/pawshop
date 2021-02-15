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
            $table->unsignedBigInteger('tip_id')->nullable();
            $table->string('name');
            $table->json('items');
            $table->boolean('is_condition')->default(0);
            $table->boolean('is_checkboxes')->default(0);
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
        Schema::dropIfExists('steps');
    }
}
