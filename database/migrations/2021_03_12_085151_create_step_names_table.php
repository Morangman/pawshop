<?php

declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateStepNamesTable
 */
class CreateStepNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('step_names', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tip_id')->nullable();
            $table->string('name');
            $table->string('title')->nullable();
            $table->integer('status')->default(1);
            $table->boolean('is_condition')->default(0);
            $table->boolean('is_checkbox')->default(0);
            $table->boolean('is_functional')->default(0);
            $table->timestamps();

            $table->foreign('tip_id')
                ->references('id')
                ->on('tips')
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
        Schema::dropIfExists('step_names');
    }
}
