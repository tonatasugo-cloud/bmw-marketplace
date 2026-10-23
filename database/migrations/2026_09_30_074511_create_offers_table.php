<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('car_id')->constrained()->cascadeOnDelete();
            $table->bigInteger('amount');
            $table->text('message')->nullable();
            $table->enum('status', [
                'pending', 'accepted', 'rejected',
                'countered', 'expired', 'cancelled'
            ])->default('pending');
            $table->foreignId('parent_offer_id')->nullable()
                  ->constrained('offers')->nullOnDelete();
            $table->foreignId('sender_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};