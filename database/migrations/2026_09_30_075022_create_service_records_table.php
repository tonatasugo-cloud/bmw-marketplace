<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('car_id')->constrained();
            $table->enum('type', [
                'oil_service', 'brake', 'tire', 'general_inspection',
                'ac', 'battery', 'other'
            ]);
            $table->integer('mileage');
            $table->date('service_date');
            $table->text('notes')->nullable();
            $table->decimal('cost', 12, 2)->nullable();
            $table->string('workshop')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_records');
    }
};