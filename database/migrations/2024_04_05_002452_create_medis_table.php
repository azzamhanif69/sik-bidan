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
        Schema::create('medis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->foreignUuid('obat_id')->constrained('obats')->onDelete('cascade');
            $table->string('nama_pasien');
            $table->string('keluhan');
            $table->string('resep');
            $table->string('aturan');
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medis');
    }
};
