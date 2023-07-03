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
        Schema::create('dirty_state_data', function (Blueprint $table) {
            $table->id();
            $table->string('source');
            $table->string('url');
            $table->string('hash')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('images')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('city')->nullable();
            $table->string('price')->nullable();
            $table->string('type')->nullable(); // аренда или продажа
            $table->string('shape')->nullable(); // квартира, дом, земля
            $table->string('owner')->nullable(); // агенство или частное лицо
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dirty_state_data');
    }
};
