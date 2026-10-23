<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('car_id')->constrained();
            $table->foreignId('seller_id')->constrained('sellers');
            $table->bigInteger('car_price');
            $table->bigInteger('booking_fee')->default(10000000);
            $table->bigInteger('total_paid')->default(0);
            $table->bigInteger('remaining_payment')->default(0);
            $table->enum('status', [
                'created', 'payment_pending', 'payment_verified',
                'reserved', 'document_processing', 'ready_delivery',
                'delivered', 'completed', 'cancelled'
            ])->default('created');
            $table->enum('delivery_method', ['pickup', 'delivery'])->default('pickup');
            $table->text('delivery_address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};