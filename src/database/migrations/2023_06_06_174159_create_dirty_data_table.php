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
        Schema::create('dirty_data', function (Blueprint $table) {
            $table->id();
            $table->string('source');
            $table->string('hash');
            $table->string('name')->nullable();
            $table->string('industry')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->string('delatnost')->nullable();
            $table->string('phone')->nullable();
            $table->string('vlasnik')->nullable();
            $table->string('zastupnik')->nullable();
            $table->string('category')->nullable();
            $table->string('subcategory')->nullable();
            $table->string('site')->nullable();
            $table->string('adscheck')->nullable();
            $table->string('rate')->nullable();
            $table->string('employees')->nullable();
            $table->string('country')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dirty_data');
    }
};
