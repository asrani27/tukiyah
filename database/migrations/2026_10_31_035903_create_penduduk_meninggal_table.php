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
        Schema::create('penduduk_meninggal', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kematian', 30)->unique();
            $table->foreignId('penduduk_id')->constrained('penduduk')->cascadeOnDelete();
            $table->date('tanggal_meninggal')->index();
            $table->string('tempat_meninggal', 100);
            $table->string('sebab_meninggal', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penduduk_meninggal');
    }
};
