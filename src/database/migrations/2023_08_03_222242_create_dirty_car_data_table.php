<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dirty_car_data', function (Blueprint $table) {
            $table->id();
            $table->string('source');
            $table->string('url');
            $table->string('hash')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->text('images')->nullable();
            $table->string('price')->nullable();
            $table->string('model')->nullable();
            $table->string('brand')->nullable();
            $table->string('year')->nullable();
            $table->string('mileage')->nullable();
            $table->string('engine_type')->nullable();
            $table->string('engine_volume')->nullable();
            $table->string('transmission')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dirty_car_data');
    }
};
