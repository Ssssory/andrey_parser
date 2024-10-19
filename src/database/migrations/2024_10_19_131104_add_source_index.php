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
        Schema::table('dirty_car_data', function (Blueprint $table) {
            $table->index('source', 'source_index');
        });
        Schema::table('dirty_state_data', function (Blueprint $table) {
            $table->index('source', 'source_index');
        });
        Schema::table('urls', function (Blueprint $table) {
            $table->index('source', 'source_index');
        });
        Schema::table('complete_messages', function (Blueprint $table) {
            $table->index(['messenger', 'model'], 'messenger_model_index');
        });
        Schema::table('complete_messages', function (Blueprint $table) {
            $table->index(['messenger', 'model', 'created_at'], 'messenger_model_created_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dirty_car_data', function (Blueprint $table) {
            $table->dropIndex('source_index');
        });
        Schema::table('dirty_state_data', function (Blueprint $table) {
            $table->dropIndex('source_index');
        });
        Schema::table('urls', function (Blueprint $table) {
            $table->dropIndex('source_index');
        });
        Schema::table('complete_messages', function (Blueprint $table) {
            $table->dropIndex('messenger_model_index');
        });
        Schema::table('complete_messages', function (Blueprint $table) {
            $table->dropIndex('messenger_model_created_at_index');
        });
    }
};
