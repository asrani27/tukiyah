<?php

namespace Tests\Feature;

use App\Models\Desa;
use App\Models\Penduduk;
use App\Models\PendudukLahir;
use App\Models\PendudukMeninggal;
use App\Models\PendudukPendatang;
use App\Models\PendudukPindah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanPdfTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Desa $desa;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->desa = Desa::create([
            'nama_desa' => 'Bati-Bati',
            'kepala_desa' => 'H. Mulyadi',
        ]);
    }

    /**
     * Test halaman laporan filter dan pratinjau tabel.
     */
    public function test_laporan_index_filters_and_previews(): void
    {
        $penduduk = Penduduk::create([
            'nik' => '6301011111900001',
            'desa_id' => $this->desa->id,
            'nama_lengkap' => 'Warga Percobaan',
            'tempat_lahir' => 'Bati-Bati',
            'tanggal_lahir' => '1990-11-11',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'status_perkawinan' => 'Kawin',
            'alamat' => 'RT 01',
            'status_keberadaan' => 'tetap',
        ]);

        $response = $this->actingAs($this->user)->get('/laporan?jenis=penduduk_tetap&desa_id='.$this->desa->id);
        $response->assertStatus(200);
        $response->assertSee('Warga Percobaan');
        $response->assertSee('Data Penduduk Tetap');

        // Test filter jenis lainnya
        $this->actingAs($this->user)->get('/laporan?jenis=penduduk_pindah')->assertStatus(200);
        $this->actingAs($this->user)->get('/laporan?jenis=penduduk_meninggal')->assertStatus(200);
        $this->actingAs($this->user)->get('/laporan?jenis=penduduk_lahir')->assertStatus(200);
        $this->actingAs($this->user)->get('/laporan?jenis=penduduk_pendatang')->assertStatus(200);
    }

    /**
     * Test cetak PDF Laporan Penduduk Tetap.
     */
    public function test_cetak_pdf_penduduk_tetap(): void
    {
        Penduduk::create([
            'nik' => '6301011111900002',
            'desa_id' => $this->desa->id,
            'nama_lengkap' => 'Budi Penduduk Tetap',
            'tempat_lahir' => 'Bati-Bati',
            'tanggal_lahir' => '1992-05-10',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'status_perkawinan' => 'Kawin',
            'alamat' => 'RT 02',
            'status_keberadaan' => 'tetap',
        ]);

        $response = $this->actingAs($this->user)->get('/laporan/cetak-pdf?jenis=penduduk_tetap&desa_id='.$this->desa->id);
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertNotEmpty($response->getContent());
    }

    /**
     * Test cetak PDF Laporan Penduduk Pindah.
     */
    public function test_cetak_pdf_penduduk_pindah(): void
    {
        $p = Penduduk::create([
            'nik' => '6301011111900003',
            'desa_id' => $this->desa->id,
            'nama_lengkap' => 'Pindah Man',
            'tempat_lahir' => 'Bati-Bati',
            'tanggal_lahir' => '1993-06-11',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'status_perkawinan' => 'Kawin',
            'alamat' => 'RT 03',
            'status_keberadaan' => 'tetap',
        ]);

        PendudukPindah::create([
            'kode_pindah' => 'PND-202610-0099',
            'penduduk_id' => $p->id,
            'alamat_tujuan' => 'Jl. Pelaihari Raya No. 10',
            'tanggal_pindah' => '2026-10-15',
            'alasan_pindah' => 'Pindah domisili',
        ]);

        $response = $this->actingAs($this->user)->get('/laporan/cetak-pdf?jenis=penduduk_pindah');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertNotEmpty($response->getContent());
    }

    /**
     * Test cetak PDF Laporan Penduduk Meninggal.
     */
    public function test_cetak_pdf_penduduk_meninggal(): void
    {
        $p = Penduduk::create([
            'nik' => '6301011111900004',
            'desa_id' => $this->desa->id,
            'nama_lengkap' => 'Almarhum Budi',
            'tempat_lahir' => 'Bati-Bati',
            'tanggal_lahir' => '1960-03-20',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'status_perkawinan' => 'Kawin',
            'alamat' => 'RT 04',
            'status_keberadaan' => 'tetap',
        ]);

        PendudukMeninggal::create([
            'kode_kematian' => 'KMT-202610-0099',
            'penduduk_id' => $p->id,
            'tanggal_meninggal' => '2026-10-14',
            'tempat_meninggal' => 'Rumah Duka',
            'sebab_meninggal' => 'Usia Lanjut',
        ]);

        $response = $this->actingAs($this->user)->get('/laporan/cetak-pdf?jenis=penduduk_meninggal');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertNotEmpty($response->getContent());
    }

    /**
     * Test cetak PDF Laporan Kelahiran.
     */
    public function test_cetak_pdf_penduduk_lahir(): void
    {
        PendudukLahir::create([
            'kode_kelahiran' => 'LHR-202610-0099',
            'desa_id' => $this->desa->id,
            'nama_bayi' => 'Bayi Sehat',
            'tempat_lahir' => 'Bati-Bati',
            'tanggal_lahir' => '2026-10-10',
            'jenis_kelamin' => 'P',
        ]);

        $response = $this->actingAs($this->user)->get('/laporan/cetak-pdf?jenis=penduduk_lahir');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertNotEmpty($response->getContent());
    }

    /**
     * Test cetak PDF Laporan Pendatang.
     */
    public function test_cetak_pdf_penduduk_pendatang(): void
    {
        PendudukPendatang::create([
            'nik' => '3301012010900009',
            'desa_id' => $this->desa->id,
            'nama_lengkap' => 'Warga Datang',
            'tempat_lahir' => 'Semarang',
            'tanggal_lahir' => '1995-04-12',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'alamat_asal' => 'Semarang Tengah',
            'tanggal_datang' => '2026-10-05',
        ]);

        $response = $this->actingAs($this->user)->get('/laporan/cetak-pdf?jenis=penduduk_pendatang');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertNotEmpty($response->getContent());
    }

    /**
     * Test all PDF report templates include official Kop logo.
     */
    public function test_all_pdf_templates_contain_official_logo(): void
    {
        $views = [
            'laporan.pdf.penduduk',
            'laporan.pdf.pindah',
            'laporan.pdf.meninggal',
            'laporan.pdf.lahir',
            'laporan.pdf.pendatang',
        ];

        foreach ($views as $viewName) {
            $html = view($viewName, [
                'data' => collect(),
                'judulLaporan' => 'LAPORAN TEST',
                'periodeText' => '2026',
                'desa' => null,
                'tanggalCetak' => '31 Oktober 2026',
            ])->render();

            $this->assertStringContainsString('logo/tala.png', $html, "View {$viewName} does not contain logo/tala.png");
            $this->assertStringContainsString('kop-table', $html, "View {$viewName} does not contain kop-table");
        }
    }
}
