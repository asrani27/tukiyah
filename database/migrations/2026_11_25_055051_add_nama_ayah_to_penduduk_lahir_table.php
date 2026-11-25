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
        Schema::table('penduduk_lahir', function (Blueprint $table) {
            $table->string('nama_ayah', 100)->nullable()->after('nik_ayah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penduduk_lahir', function (Blueprint $table) {
            $table->dropColumn('nama_ayah');
        });
    }
};
