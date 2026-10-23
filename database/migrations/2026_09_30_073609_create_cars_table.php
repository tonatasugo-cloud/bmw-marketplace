<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained()->cascadeOnDelete();
            $table->foreignId('car_series_id')->constrained('car_series');
            $table->string('title');
            $table->string('slug')->unique();
            $table->year('year');
            $table->bigInteger('price');
            $table->integer('mileage');
            $table->string('transmission');
            $table->string('fuel_type');
            $table->decimal('engine_capacity', 3, 1);
            $table->string('drivetrain');
            $table->string('exterior_color');
            $table->string('interior_color')->nullable();
            $table->string('body_type');
            $table->enum('condition', ['excellent', 'good', 'fair']);
            $table->boolean('is_certified')->default(false);
            $table->string('location_city');
            $table->string('location_province');
            $table->string('plate_number')->nullable();
            $table->string('vin')->nullable();
            $table->integer('seat_capacity')->default(5);
            $table->integer('door_count')->default(4);
            $table->text('description');
            $table->json('feature_list')->nullable();
            $table->enum('status', [
                'draft', 'pending', 'available', 'reserved',
                'sold', 'rejected', 'archived'
            ])->default('pending');
            $table->boolean('is_featured')->default(false);
            $table->integer('view_count')->default(0);
            $table->integer('wishlist_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('sold_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'is_featured']);
            $table->index(['status', 'price']);
            $table->index(['status', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};