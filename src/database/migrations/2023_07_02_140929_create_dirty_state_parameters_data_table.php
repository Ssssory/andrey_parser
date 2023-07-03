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
        Schema::create('dirty_state_parameters_data', function (Blueprint $table) {
            $table->id();
            $table->integer('state_id');
            $table->string('property');
            $table->string('name')->nullable();
            $table->string('value');
            $table->boolean('is_appruved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dirty_state_parameters_data');
    }
};
