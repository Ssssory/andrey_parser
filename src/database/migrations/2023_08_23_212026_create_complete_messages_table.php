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
        Schema::create('complete_messages', function (Blueprint $table) {
            $table->id();
            $table->string('model');
            $table->integer('model_id');
            $table->string('comment')->nullable();
            $table->string('message')->comment('dto type');
            $table->string('chat');
            $table->string('messenger')->default('telegram');
            $table->string('type')->nullable()->comment('auto or handle');
            $table->boolean('success')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complete_messages');
    }
};
