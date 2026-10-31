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
        Schema::create('penduduk', function (Blueprint $table) {
            $table->id();
            $table->char('nik', 16)->unique();
            $table->foreignId('desa_id')->constrained('desa')->cascadeOnDelete();
            $table->string('nama_lengkap', 100);
            $table->string('tempat_lahir', 50);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('agama', 20);
            $table->string('status_perkawinan', 30);
            $table->string('pendidikan_terakhir', 30)->nullable();
            $table->text('alamat');
            $table->string('telp', 20)->nullable();
            $table->enum('status_keberadaan', ['tetap', 'pindah', 'meninggal'])->default('tetap')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penduduk');
    }
};
