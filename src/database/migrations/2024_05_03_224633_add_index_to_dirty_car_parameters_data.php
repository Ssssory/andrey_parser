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
        Schema::table('dirty_car_parameters_data', function (Blueprint $table) {
            $table->index('car_id');
            $table->index(['property', 'is_appruved']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dirty_car_parameters_data', function (Blueprint $table) {
            $table->dropIndex(['car_id']);
        });
    }
};
