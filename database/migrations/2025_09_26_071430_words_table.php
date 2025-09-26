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
        Schema::create('words', function(Blueprint $table) {
            $table->id();
            $table->integer('groups_id');
            $table->integer('order_number');
            $table->string('word');
            $table->string('translation');
            $table->string('kalimat');
            $table->string('jenis');
            $table->string('hukum');
            $table->string('mabni_detail');
            $table->string('kategori');
            $table->string('kedudukan');
            $table->string('yang_diikuti');
            $table->string('irab');
            $table->string('tanda');
            $table->string('nampak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('words');
    }
};
