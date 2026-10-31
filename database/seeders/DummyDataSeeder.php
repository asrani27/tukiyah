<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\Penduduk;
use App\Models\PendudukLahir;
use App\Models\PendudukMeninggal;
use App\Models\PendudukPendatang;
use App\Models\PendudukPindah;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $desas = Desa::all();
        if ($desas->isEmpty()) {
            return;
        }

        $now = Carbon::now();

        $residents = [
            ['6301031501850001', 'Muhammad Rifqi Maulana', 'Pelaihari', '1985-01-15', 'L', 'Islam', 'Kawin', 'S1', 'Jl. A Yani RT 02', '081234567891'],
            ['6301035203870002', 'Siti Nurhaliza', 'Bati-Bati', '1987-03-12', 'P', 'Islam', 'Kawin', 'SMA/SMK', 'Jl. A Yani RT 02', '081234567892'],
            ['6301032007900003', 'Bambang Irawan', 'Banjarmasin', '1990-07-20', 'L', 'Islam', 'Kawin', 'D3', 'Jl. Trans Kalsel RT 01', '081234567893'],
            ['6301036509920004', 'Dewi Sartika', 'Bati-Bati', '1992-09-25', 'P', 'Islam', 'Kawin', 'S1', 'Jl. Trans Kalsel RT 01', '081234567894'],
            ['6301030502750005', 'H. Anang Subarjo', 'Tanah Laut', '1975-02-05', 'L', 'Islam', 'Kawin', 'SMA/SMK', 'Jl. Padang Raya RT 03', '081234567895'],
            ['6301031208980007', 'Ahmad Faisal', 'Bati-Bati', '1998-08-12', 'L', 'Islam', 'Belum Kawin', 'S1', 'Jl. Kait-Kait RT 01', '081234567897'],
            ['6301035511990008', 'Nurul Hidayah', 'Bati-Bati', '1999-11-15', 'P', 'Islam', 'Belum Kawin', 'SMA/SMK', 'Jl. Kait-Kait RT 01', '081234567898'],
            ['6301031006820009', 'Joko Susanto', 'Solo', '1982-06-10', 'L', 'Islam', 'Kawin', 'SMA/SMK', 'Jl. Bentok Swadaya RT 02', '081234567899'],
            ['6301031402800011', 'Surya Kencana', 'Pelaihari', '1980-02-14', 'L', 'Islam', 'Kawin', 'SMA/SMK', 'Jl. Beringin RT 02', '081234567813'],
            ['6301030101500012', 'Kakek Sanusi', 'Bati-Bati', '1950-01-01', 'L', 'Islam', 'Cerai Mati', 'SD', 'Jl. Nusa Indah RT 01', null],
        ];

        $saved = [];
        foreach ($residents as $i => $r) {
            $desa = $desas[$i % $desas->count()];
            $saved[] = Penduduk::create([
                'nik' => $r[0],
                'desa_id' => $desa->id,
                'nama_lengkap' => $r[1],
                'tempat_lahir' => $r[2],
                'tanggal_lahir' => $r[3],
                'jenis_kelamin' => $r[4],
                'agama' => $r[5],
                'status_perkawinan' => $r[6],
                'pendidikan_terakhir' => $r[7],
                'alamat' => $r[8],
                'telp' => $r[9],
                'status_keberadaan' => 'tetap',
            ]);
        }

        // Pindah
        if (isset($saved[8])) {
            PendudukPindah::create([
                'kode_pindah' => 'PND-'.date('Ym').'-0001',
                'penduduk_id' => $saved[8]->id,
                'alamat_tujuan' => 'Jl. Perintis Kemerdekaan No. 88, Banjarmasin',
                'tanggal_pindah' => $now->copy()->subDays(5)->format('Y-m-d'),
                'alasan_pindah' => 'Pindah tugas pekerjaan',
            ]);
        }

        // Meninggal
        if (isset($saved[9])) {
            PendudukMeninggal::create([
                'kode_kematian' => 'KMT-'.date('Ym').'-0001',
                'penduduk_id' => $saved[9]->id,
                'tanggal_meninggal' => $now->copy()->subDays(3)->format('Y-m-d'),
                'tempat_meninggal' => 'RSUD H. Boejasin Pelaihari',
                'sebab_meninggal' => 'Sakit Usia Lanjut',
            ]);
        }

        // Lahir
        PendudukLahir::create([
            'kode_kelahiran' => 'LHR-'.date('Ym').'-0001',
            'desa_id' => $desas[0]->id,
            'nik_ibu' => '6301035203870002',
            'nik_ayah' => '6301031501850001',
            'nama_bayi' => 'Muhammad Rayyan Al-Ghifari',
            'tempat_lahir' => 'Bati-Bati',
            'tanggal_lahir' => $now->copy()->subDays(4)->format('Y-m-d'),
            'jenis_kelamin' => 'L',
        ]);

        // Pendatang
        PendudukPendatang::create([
            'nik' => '6201021404930001',
            'desa_id' => $desas[0]->id,
            'nama_lengkap' => 'Danang Prasetyo',
            'tempat_lahir' => 'Palangka Raya',
            'tanggal_lahir' => '1993-04-14',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'alamat_asal' => 'Jl. RTA Milono Km. 4, Palangka Raya',
            'tanggal_datang' => $now->copy()->subDays(6)->format('Y-m-d'),
            'telp' => '085249112233',
        ]);
    }
}
