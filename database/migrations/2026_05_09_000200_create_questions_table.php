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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255); // Judul soal
            $table->text('description')->nullable(); // Deskripsi atau konteks soal
            $table->text('content'); // Isi soal
            $table->tinyInteger('level')->default(1); // Level 1, 2, 3
            $table->enum('type', ['multiple_choice', 'short_answer', 'essay', 'analysis'])->default('multiple_choice'); // Tipe soal
            $table->json('options')->nullable(); // Untuk multiple choice: {a: "...", b: "...", c: "...", d: "..."}
            $table->text('correct_answer')->nullable(); // Jawaban benar
            $table->text('explanation')->nullable(); // Penjelasan jawaban
            $table->integer('display_order')->nullable(); // Urutan tampilan
            $table->boolean('is_active')->default(true); // Status soal aktif atau tidak
            $table->integer('attempts')->default(0); // Berapa kali soal ini dijawab
            $table->integer('passed')->default(0); // Berapa banyak yang berhasil
            $table->json('metadata')->nullable(); // Data tambahan: tags, references, difficulty_score, dll
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Pembuat soal
            $table->timestamps();

            // Indexes
            $table->index(['level', 'type']);
            $table->index(['is_active']);
            $table->index(['created_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
