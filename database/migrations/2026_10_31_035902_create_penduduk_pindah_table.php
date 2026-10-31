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
        Schema::create('penduduk_pindah', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pindah', 30)->unique();
            $table->foreignId('penduduk_id')->constrained('penduduk')->cascadeOnDelete();
            $table->text('alamat_tujuan');
            $table->date('tanggal_pindah')->index();
            $table->text('alasan_pindah')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penduduk_pindah');
    }
};
