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
        Schema::create('property_value_dictionaries', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('property_dictionaries_uuid')->references('uuid')->on('property_dictionaries');
            $table->string('name')->comment('original value');
            $table->string('group')->comment('SourceType');
            $table->string('ru')->nullable();
            $table->string('en')->nullable();
            $table->string('rs')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_value_dictionaries');
    }
};
