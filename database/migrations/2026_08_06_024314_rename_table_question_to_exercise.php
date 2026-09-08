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
        Schema::rename('user_answers', 'exercise_submissions');

        Schema::table('exercises', function (Blueprint $table) {
            $table->renameColumn('level', 'level_id');
        });

        Schema::table('exercise_submissions', function (Blueprint $table) {
            $table->renameColumn('question_id', 'exercise_id');
            $table->renameColumn('level', 'level_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('exercises', 'questions');
        Schema::rename('exercise_levels', 'question_levels');
        Schema::rename('exercise_submissions', 'user_answers');

        Schema::table('exercises', function (Blueprint $table) {
            $table->renameColumn('level_id', 'level');
        });

        Schema::table('user_answers', function (Blueprint $table) {
            $table->renameColumn('exercise_id', 'question_id');
            $table->renameColumn('level_id', 'level');
        });
    }
};
