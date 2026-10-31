<?php

namespace Database\Seeders;

use App\Models\Desa;
use Illuminate\Database\Seeder;

class DesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $desaList = [
            [
                'nama_desa' => 'Bati-Bati',
                'kepala_desa' => 'Ahmad Riduan, S.Sos',
                'telp' => '08125011001',
                'alamat' => 'Jl. A. Yani Km. 42, RT 03 RW 01, Kec. Bati-Bati',
            ],
            [
                'nama_desa' => 'Padang',
                'kepala_desa' => 'H. M. Noor',
                'telp' => '08125011002',
                'alamat' => 'Jl. Trans Kalimantan No. 12, Kec. Bati-Bati',
            ],
            [
                'nama_desa' => 'Kait-Kait',
                'kepala_desa' => 'M. Syahrani',
                'telp' => '08125011003',
                'alamat' => 'Jl. Poros Perkebunan RT 02 RW 01, Kec. Bati-Bati',
            ],
            [
                'nama_desa' => 'Bentok Kampung',
                'kepala_desa' => 'H. Rusli',
                'telp' => '08125011004',
                'alamat' => 'Jl. Swadaya Desa Bentok Kampung, Kec. Bati-Bati',
            ],
            [
                'nama_desa' => 'Bentok Darat',
                'kepala_desa' => 'Supriyadi',
                'telp' => '08125011005',
                'alamat' => 'Jl. Pendidikan RT 04 RW 02, Kec. Bati-Bati',
            ],
            [
                'nama_desa' => 'Benua Raya',
                'kepala_desa' => 'H. Jamhuri',
                'telp' => '08125011006',
                'alamat' => 'Jl. Melati RT 01 RW 01, Kec. Bati-Bati',
            ],
            [
                'nama_desa' => 'Sambangan',
                'kepala_desa' => 'Muhammad Arsyad',
                'telp' => '08125011007',
                'alamat' => 'Jl. Beringin RT 02 RW 01, Kec. Bati-Bati',
            ],
            [
                'nama_desa' => 'Liang Anggang',
                'kepala_desa' => 'H. Suwardi',
                'telp' => '08125011008',
                'alamat' => 'Jl. Raya Liang Anggang RT 03 RW 01, Kec. Bati-Bati',
            ],
            [
                'nama_desa' => 'Nusa',
                'kepala_desa' => 'Abdul Khair',
                'telp' => '08125011009',
                'alamat' => 'Jl. Pahlawan RT 01 RW 01, Kec. Bati-Bati',
            ],
            [
                'nama_desa' => 'Ujung',
                'kepala_desa' => 'Samsul Bahri',
                'telp' => '08125011010',
                'alamat' => 'Jl. Pertanian RT 02 RW 01, Kec. Bati-Bati',
            ],
            [
                'nama_desa' => 'Banyuirang',
                'kepala_desa' => 'H. Anang Suriansyah',
                'telp' => '08125011011',
                'alamat' => 'Jl. Banyuirang Permai RT 03 RW 02, Kec. Bati-Bati',
            ],
            [
                'nama_desa' => 'Pandahan',
                'kepala_desa' => 'Syamsul Ma\'arif',
                'telp' => '08125011012',
                'alamat' => 'Jl. Swakarya RT 01 RW 01, Kec. Bati-Bati',
            ],
        ];

        foreach ($desaList as $data) {
            Desa::updateOrCreate(
                ['nama_desa' => $data['nama_desa']],
                $data
            );
        }
    }
}
