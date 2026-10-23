<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('payment_number')->unique();
            $table->enum('method', ['bank_transfer', 'ewallet', 'virtual_account']);
            $table->bigInteger('amount');
            $table->string('bank_name')->nullable();
            $table->string('va_number')->nullable();
            $table->string('proof_path')->nullable();
            $table->enum('status', [
                'pending', 'waiting_verification',
                'verified', 'rejected', 'refunded'
            ])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};