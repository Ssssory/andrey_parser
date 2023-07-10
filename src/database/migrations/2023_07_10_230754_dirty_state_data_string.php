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
        Schema::table('dirty_state_data', function (Blueprint $table) {
            $table->text('images')->change();
            $table->text('description')->change();
        });
    }
};
