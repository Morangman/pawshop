<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateCategoriesTable
 */
class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->unsignedBigInteger('faq_id')->nullable();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('text')->nullable();
            $table->string('image')->nullable();
            $table->string('custom_text')->nullable();
            $table->string('premium_price')->nullable();
            $table->string('price_for_broken')->default('5.00');
            $table->boolean('is_hidden')->default(0);
            $table->boolean('is_parsed')->default(0);
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
        Schema::dropIfExists('categories');
    }
}
