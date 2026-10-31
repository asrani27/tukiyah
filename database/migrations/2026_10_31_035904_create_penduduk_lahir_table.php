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
        Schema::create('penduduk_lahir', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kelahiran', 30)->unique();
            $table->foreignId('desa_id')->constrained('desa')->cascadeOnDelete();
            $table->char('nik_ibu', 16)->nullable();
            $table->char('nik_ayah', 16)->nullable();
            $table->string('nama_bayi', 100);
            $table->string('tempat_lahir', 50);
            $table->date('tanggal_lahir')->index();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penduduk_lahir');
    }
};
