<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('medis', function (Blueprint $table) {
            $table->text('pemeriksaan')->nullable();
            $table->text('kesimpulan')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medis', function (Blueprint $table) {
            //
        });
    }
};
