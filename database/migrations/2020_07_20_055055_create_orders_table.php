<?php

declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateOrdersTable
 */
class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('tracking_number', 64)->nullable();
            $table->text('notes')->nullable();
            $table->json('orders');
            $table->string('user_email')->nullable();
            $table->string('total_summ');
            $table->json('payment');
            $table->json('address');
            $table->string('comment')->nullable();
            $table->string('exp_service')->nullable();
            $table->string('insurance')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('fedex_status')->nullable();
            $table->tinyInteger('ordered_status')->unsigned()->default(1);
            $table->tinyInteger('is_transit_notify')->unsigned()->nullable();
            $table->tinyInteger('is_received_notify')->unsigned()->nullable();
            $table->boolean('is_label_trouble')->default(0);
            $table->boolean('is_restored')->default(0);
            $table->timestamp('estimate_date')->nullable();
            $table->timestamp('delivered_date')->nullable();
            $table->decimal('delivery_price', 10, 2)->default(0);
            $table->timestamp('paid_date')->nullable();
            $table->timestamp('received_date')->nullable();
            $table->timestamp('cancelled_date')->nullable();
            $table->unsignedBigInteger('send_ctn')->default(0);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });

        DB::statement("ALTER TABLE orders AUTO_INCREMENT = 12120;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
}
