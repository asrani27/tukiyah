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
        Schema::create('penduduk_pendatang', function (Blueprint $table) {
            $table->id();
            $table->char('nik', 16)->unique();
            $table->foreignId('desa_id')->constrained('desa')->cascadeOnDelete();
            $table->string('nama_lengkap', 100);
            $table->string('tempat_lahir', 50);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('agama', 20);
            $table->text('alamat_asal');
            $table->date('tanggal_datang')->index();
            $table->string('telp', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penduduk_pendatang');
    }
};
