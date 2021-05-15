<?php

declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateWarehousesTable
 */
class CreateWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->tinyInteger('status')->unsigned()->default(1);
            $table->string('product_name')->nullable();
            $table->string('imei')->nullable();
            $table->string('serial_number')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('clear_price', 10, 2)->default(0);
            $table->decimal('delivery_price', 10, 2)->default(0);
            $table->decimal('repair_price', 10, 2)->default(0);
            $table->decimal('sell_price', 10, 2)->default(0);
            $table->boolean('is_locked')->default(0);
            $table->json('steps');
            $table->boolean('exp_service')->nullable();
            $table->boolean('insurance')->nullable();
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
        Schema::dropIfExists('warehouses');
    }
}
