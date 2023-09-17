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
        Schema::create('group_bot', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('group_id');
            $table->unsignedBiginteger('bot_id');


            $table->foreign('group_id')->references('id')
                ->on('groups')->onDelete('cascade');
            $table->foreign('bot_id')->references('id')
                ->on('bots')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_bot');
    }
};
