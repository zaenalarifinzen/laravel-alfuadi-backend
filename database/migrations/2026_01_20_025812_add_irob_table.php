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
        // add kalimat table
        Schema::create('kalimat', function (Blueprint $table) {
            $table->string('id');
            $table->primary('id');

            $table->string('kalimat_ar');
            $table->string('kalimat_ar_musyakal');
            $table->string('kalimat_in');
        });

        // add kategori table
        Schema::create('kategori', function (Blueprint $table) {
            $table->string('id');
            $table->primary('id');
            
            $table->string('id_kalimat');
            $table->foreign('id_kalimat')->references('id')->on('kalimat')->onDelete('cascade');

            $table->integer('order');
            $table->string('simbol')->nullable();;
            $table->string('kategori_ar');
            $table->string('kategori_ar_musyakal');
            $table->string('kategori_in');
            $table->string('hukum')->nullable();;
            $table->string('rofa')->nullable();;
            $table->string('nashob')->nullable();;
            $table->string('jar')->nullable();;
            $table->string('jazm')->nullable();;
        });

        // add kedudukan table
        Schema::create('kedudukan', function (Blueprint $table) {
            $table->string('id');
            $table->primary('id');

            $table->string('id_kalimat');
            $table->foreign('id_kalimat')->references('id')->on('kalimat')->onDelete('cascade');

            $table->integer('order');
            $table->string('simbol')->nullable();;
            $table->string('kedudukan_ar');
            $table->string('kedudukan_ar_musyakal');
            $table->string('kedudukan_in');
            $table->string('irob')->nullable();;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kalimat');
        Schema::dropIfExists('kategori');
        Schema::dropIfExists('kedudukan');
    }
};
