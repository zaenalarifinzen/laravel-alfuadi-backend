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
        Schema::rename('questions', 'exercises');
        Schema::rename('question_levels', 'exercise_levels');

        Schema::table('user_answers', function (Blueprint $table) {
            $table->renameColumn('question_id', 'exercise_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('exercises', 'questions');
        Schema::rename('exercise_levels', 'question_levels');

        Schema::table('user_answers', function (Blueprint $table) {
            $table->renameColumn('exercise_id', 'question_id');
        });
    }
};
