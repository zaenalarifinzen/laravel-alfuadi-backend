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
        Schema::create('user_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->tinyInteger('level')->default(1); // 1, 2, 3
            $table->boolean('passed')->default(false);
            $table->decimal('score', 5, 2)->nullable();
            $table->integer('attempt_count')->default(1);
            $table->integer('time_spent')->nullable(); // dalam detik
            $table->boolean('is_latest')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Indexes untuk query performance
            $table->index(['user_id', 'level']);
            $table->index(['user_id', 'question_id']);
            $table->index(['passed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_answers');
    }
};
