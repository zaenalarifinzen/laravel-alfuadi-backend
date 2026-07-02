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
        // add created_at and updated_at columns to kalimat_kedudukan_kategori table
        Schema::table('kalimat', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('kedudukan', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('kategori', function (Blueprint $table) {
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kalimat', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('kategori', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('kedudukan', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};
