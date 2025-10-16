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
        // Surahs Table
        Schema::create('surahs', function (Blueprint $table) {
            $table->integer('id', true, true);
            $table->primary('id');

            $table->string('name', 100);
            $table->string('name_id', 100);
            $table->string('name_en', 100);
            $table->string('location', 100);
            $table->integer('verse_count');
        });

        // Verses Table
        Schema::create('verses', function (Blueprint $table) {
            $table->integer('id', true, true);
            $table->primary('id');

            $table->unsignedInteger('surah_id');
            $table->foreign('surah_id')->references('id')->on('surahs')->onDelete('cascade');
            $table->integer('number');
            $table->text('text');
            $table->text('translation_indo')->nullable();
        });

        // Word Groups Table
        Schema::create('word_groups', function (Blueprint $table) {
            $table->integer('id', true, true);
            $table->primary('id');

            $table->unsignedInteger('surah_id');
            $table->foreign('surah_id')->references('id')->on('surahs')->onDelete('cascade');

            $table->unsignedInteger('verse_number');
            $table->foreign('verse_number')->references('id')->on('verses')->onDelete('cascade');

            $table->unsignedInteger('verse_id')->nullable();
            $table->foreign('verse_id')->references('id')->on('verses')->onDelete('cascade');

            $table->integer('order_number')->nullable();

            $table->text('text');
        });

        // Words Table
        Schema::create('words', function (Blueprint $table) {
            $table->integer('id', true, true);
            $table->primary('id');

            $table->unsignedInteger('word_group_id');
            $table->foreign('word_group_id')->references('id')->on('word_groups')->onDelete('cascade');

            $table->integer('order_number');
            $table->text('word');
            $table->text('translation')->nullable();
            $table->string('kalimat', 100)->nullable();
            $table->string('jenis', 100)->nullable();
            $table->string('hukum', 100)->nullable();
            $table->string('mabni_detail', 100)->nullable();
            $table->string('kategori', 100)->nullable();
            $table->string('kedudukan', 100)->nullable();
            $table->string('yang_diikuti', 100)->nullable();
            $table->string('irab', 100)->nullable();
            $table->string('tanda', 100)->nullable();
            $table->string('nampak', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('words');
        Schema::dropIfExists('word_groups');
        Schema::dropIfExists('verses');
        Schema::dropIfExists('surahs');
    }
};
