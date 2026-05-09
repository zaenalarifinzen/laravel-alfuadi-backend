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
        Schema::create('question_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100); // "Pemula", "Menengah", "Lanjutan"
            $table->integer('level_number')->unique(); // 1, 2, 3
            $table->text('description')->nullable();
            $table->string('color', 50)->nullable(); // Untuk UI: "green", "yellow", "red"
            $table->string('icon', 100)->nullable(); // Icon name
            $table->integer('min_score')->default(0); // Score minimum untuk lanjut ke level berikutnya
            $table->integer('question_count')->default(10); // Jumlah soal per level
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable(); // Data tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_levels');
    }
};
