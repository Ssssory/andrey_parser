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
        Schema::create('property_dictionaries', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('name')->comment('relation with original name');
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
        Schema::dropIfExists('property_dictionaries');
    }
};
