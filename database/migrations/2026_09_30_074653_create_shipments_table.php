<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->text('origin_address');
            $table->text('destination_address');
            $table->timestamp('estimated_arrival')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->string('proof_path')->nullable();
            $table->enum('status', [
                'prepared', 'picked_up', 'in_transit', 'delivered'
            ])->default('prepared');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};