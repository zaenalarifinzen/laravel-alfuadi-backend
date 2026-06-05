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
        Schema::table('question_levels', function (Blueprint $table) {
            if (!Schema::hasColumn('question_levels', 'slug')) {
                $table->string('slug', 100)->nullable()->unique()->after('name');
            }

            if (!Schema::hasColumn('question_levels', 'display_order')) {
                $table->integer('display_order')->nullable()->after('level_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('question_levels', function (Blueprint $table) {
            if (Schema::hasColumn('question_levels', 'slug')) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            }

            if (Schema::hasColumn('question_levels', 'display_order')) {
                $table->dropColumn('display_order');
            }
        });
    }
};
