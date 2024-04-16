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
        Schema::create('obats', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_obat');
            $table->string('sediaan');
            $table->integer('dosis');
            $table->string('satuan');
            $table->integer('stok');
            $table->string('harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obats');
        // Schema::table(
        //     'nama_tabel',
        //     function (Blueprint $table) {
        //         $table->string('harga')->change();
        //     }
        // );
    }
};
