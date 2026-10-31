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

class MutasiTest extends TestCase
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
     * Test siklus mutasi pindah dan update otomatis status keberadaan penduduk.
     */
    public function test_mutasi_pindah_lifecycle_and_status_transition(): void
    {
        $penduduk = Penduduk::create([
            'nik' => '6301011101910001',
            'desa_id' => $this->desa->id,
            'nama_lengkap' => 'Ahmad Sholihin',
            'tempat_lahir' => 'Bati-Bati',
            'tanggal_lahir' => '1991-01-11',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'status_perkawinan' => 'Kawin',
            'alamat' => 'RT 01',
            'status_keberadaan' => 'tetap',
        ]);

        // 1. Tampilkan form create mutasi pindah
        $resCreate = $this->actingAs($this->user)->get('/mutasi/pindah/create');
        $resCreate->assertStatus(200);
        $resCreate->assertSee('Ahmad Sholihin');

        // 2. Simpan mutasi pindah
        $pindahData = [
            'kode_pindah' => 'PND-202610-0001',
            'penduduk_id' => $penduduk->id,
            'alamat_tujuan' => 'Jl. Pangeran Antasari No. 45, Banjarmasin',
            'tanggal_pindah' => '2026-10-15',
            'alasan_pindah' => 'Pindah tugas pekerjaan',
        ];

        $response = $this->actingAs($this->user)->post('/mutasi/pindah', $pindahData);
        $pindah = PendudukPindah::where('kode_pindah', 'PND-202610-0001')->first();
        $this->assertNotNull($pindah);
        $response->assertRedirect("/mutasi/pindah/{$pindah->id}");

        // Status penduduk otomatis berubah menjadi 'pindah'
        $penduduk->refresh();
        $this->assertEquals('pindah', $penduduk->status_keberadaan);

        // 3. Tampilkan detail show mutasi pindah
        $resShow = $this->actingAs($this->user)->get("/mutasi/pindah/{$pindah->id}");
        $resShow->assertStatus(200);
        $resShow->assertSee('PND-202610-0001');
        $resShow->assertSee('Banjarmasin');

        // 4. Upaya memindahkan lagi penduduk yang sudah pindah harus gagal validasi
        $resInvalid = $this->actingAs($this->user)->post('/mutasi/pindah', [
            'kode_pindah' => 'PND-202610-0002',
            'penduduk_id' => $penduduk->id,
            'alamat_tujuan' => 'Alamat Lain',
            'tanggal_pindah' => '2026-10-16',
        ]);
        $resInvalid->assertSessionHasErrors('penduduk_id');

        // 5. Hapus catatan mutasi pindah dan verifikasi status penduduk pulih ke 'tetap'
        $resDelete = $this->actingAs($this->user)->delete("/mutasi/pindah/{$pindah->id}");
        $resDelete->assertRedirect('/mutasi/pindah');
        $this->assertDatabaseMissing('penduduk_pindah', ['id' => $pindah->id]);

        $penduduk->refresh();
        $this->assertEquals('tetap', $penduduk->status_keberadaan);
    }

    /**
     * Test siklus mutasi kematian dan perubahan status keberadaan ke 'meninggal'.
     */
    public function test_mutasi_meninggal_lifecycle_and_status_transition(): void
    {
        $penduduk = Penduduk::create([
            'nik' => '6301011202800002',
            'desa_id' => $this->desa->id,
            'nama_lengkap' => 'H. Bakri',
            'tempat_lahir' => 'Bati-Bati',
            'tanggal_lahir' => '1980-02-12',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'status_perkawinan' => 'Kawin',
            'alamat' => 'RT 02',
            'status_keberadaan' => 'tetap',
        ]);

        // 1. Tampilkan form create mutasi kematian
        $resCreate = $this->actingAs($this->user)->get('/mutasi/meninggal/create');
        $resCreate->assertStatus(200);
        $resCreate->assertSee('H. Bakri');

        // 2. Simpan mutasi kematian
        $meninggalData = [
            'kode_kematian' => 'KMT-202610-0001',
            'penduduk_id' => $penduduk->id,
            'tanggal_meninggal' => '2026-10-20',
            'tempat_meninggal' => 'RSUD H. Boejasin Pelaihari',
            'sebab_meninggal' => 'Sakit Jantung',
        ];

        $response = $this->actingAs($this->user)->post('/mutasi/meninggal', $meninggalData);
        $meninggal = PendudukMeninggal::where('kode_kematian', 'KMT-202610-0001')->first();
        $this->assertNotNull($meninggal);
        $response->assertRedirect("/mutasi/meninggal/{$meninggal->id}");

        // Status penduduk otomatis 'meninggal'
        $penduduk->refresh();
        $this->assertEquals('meninggal', $penduduk->status_keberadaan);

        // 3. Tampilkan show mutasi kematian
        $resShow = $this->actingAs($this->user)->get("/mutasi/meninggal/{$meninggal->id}");
        $resShow->assertStatus(200);
        $resShow->assertSee('KMT-202610-0001');
        $resShow->assertSee('RSUD H. Boejasin Pelaihari');

        // 4. Hapus data kematian dan status kembali ke 'tetap'
        $resDelete = $this->actingAs($this->user)->delete("/mutasi/meninggal/{$meninggal->id}");
        $resDelete->assertRedirect('/mutasi/meninggal');
        $this->assertDatabaseMissing('penduduk_meninggal', ['id' => $meninggal->id]);

        $penduduk->refresh();
        $this->assertEquals('tetap', $penduduk->status_keberadaan);
    }

    /**
     * Test CRUD pencatatan kelahiran.
     */
    public function test_kelahiran_crud_lifecycle(): void
    {
        // 1. Tampilkan form create kelahiran
        $resCreate = $this->actingAs($this->user)->get('/mutasi/lahir/create');
        $resCreate->assertStatus(200);

        // 2. Simpan pencatatan kelahiran
        $lahirData = [
            'kode_kelahiran' => 'LHR-202610-0001',
            'desa_id' => $this->desa->id,
            'nama_bayi' => 'Muhammad Rayyan',
            'tempat_lahir' => 'Bati-Bati',
            'tanggal_lahir' => '2026-10-18',
            'jenis_kelamin' => 'L',
            'nik_ibu' => '6301015505950001',
            'nik_ayah' => '6301011505900001',
        ];

        $resStore = $this->actingAs($this->user)->post('/mutasi/lahir', $lahirData);
        $resStore->assertRedirect('/mutasi/lahir');
        $this->assertDatabaseHas('penduduk_lahir', ['kode_kelahiran' => 'LHR-202610-0001']);

        $lahir = PendudukLahir::where('kode_kelahiran', 'LHR-202610-0001')->first();
        $this->assertNotNull($lahir);

        // 3. Tampilkan index dan pastikan nama bayi muncul
        $resIndex = $this->actingAs($this->user)->get('/mutasi/lahir');
        $resIndex->assertStatus(200);
        $resIndex->assertSee('Muhammad Rayyan');

        // 4. Tampilkan form edit kelahiran
        $resEdit = $this->actingAs($this->user)->get("/mutasi/lahir/{$lahir->id}/edit");
        $resEdit->assertStatus(200);
        $resEdit->assertSee('Muhammad Rayyan');

        // 5. Update data kelahiran
        $lahirData['nama_bayi'] = 'Muhammad Rayyan Al-Fatih';
        $resUpdate = $this->actingAs($this->user)->put("/mutasi/lahir/{$lahir->id}", $lahirData);
        $resUpdate->assertRedirect('/mutasi/lahir');
        $this->assertDatabaseHas('penduduk_lahir', ['nama_bayi' => 'Muhammad Rayyan Al-Fatih']);

        // 6. Hapus data kelahiran
        $resDelete = $this->actingAs($this->user)->delete("/mutasi/lahir/{$lahir->id}");
        $resDelete->assertRedirect('/mutasi/lahir');
        $this->assertDatabaseMissing('penduduk_lahir', ['id' => $lahir->id]);
    }

    /**
     * Test CRUD penduduk pendatang dan aksi 'Jadikan Warga Tetap'.
     */
    public function test_pendatang_crud_and_conversion_to_tetap(): void
    {
        // 1. Tampilkan form create pendatang
        $resCreate = $this->actingAs($this->user)->get('/mutasi/pendatang/create');
        $resCreate->assertStatus(200);

        // 2. Simpan penduduk pendatang
        $pendatangData = [
            'nik' => '3201012010900005',
            'desa_id' => $this->desa->id,
            'nama_lengkap' => 'Dedi Iskandar',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1990-10-20',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'alamat_asal' => 'Jl. Cibadak No. 12, Bandung Barat',
            'tanggal_datang' => '2026-10-01',
            'telp' => '081399887766',
        ];

        $resStore = $this->actingAs($this->user)->post('/mutasi/pendatang', $pendatangData);
        $resStore->assertRedirect('/mutasi/pendatang');
        $this->assertDatabaseHas('penduduk_pendatang', ['nik' => '3201012010900005']);

        $pendatang = PendudukPendatang::where('nik', '3201012010900005')->first();
        $this->assertNotNull($pendatang);

        // 3. Tampilkan index pendatang
        $resIndex = $this->actingAs($this->user)->get('/mutasi/pendatang');
        $resIndex->assertStatus(200);
        $resIndex->assertSee('Dedi Iskandar');

        // 4. Update data pendatang
        $pendatangData['telp'] = '081399887700';
        $resUpdate = $this->actingAs($this->user)->put("/mutasi/pendatang/{$pendatang->id}", $pendatangData);
        $resUpdate->assertRedirect('/mutasi/pendatang');
        $this->assertDatabaseHas('penduduk_pendatang', ['telp' => '081399887700']);

        // 5. Jadikan Penduduk Tetap
        $resJadikan = $this->actingAs($this->user)->post("/mutasi/pendatang/{$pendatang->id}/jadikan-tetap");
        $resJadikan->assertRedirect('/penduduk');
        $this->assertDatabaseHas('penduduk', [
            'nik' => '3201012010900005',
            'nama_lengkap' => 'Dedi Iskandar',
            'status_keberadaan' => 'tetap',
        ]);

        // 6. Upaya menjadikan tetap kedua kalinya harus ditolak karena NIK sudah terdaftar
        $resDuplicate = $this->actingAs($this->user)->post("/mutasi/pendatang/{$pendatang->id}/jadikan-tetap");
        $resDuplicate->assertSessionHas('error');

        // 7. Hapus data pendatang
        $resDelete = $this->actingAs($this->user)->delete("/mutasi/pendatang/{$pendatang->id}");
        $resDelete->assertRedirect('/mutasi/pendatang');
        $this->assertDatabaseMissing('penduduk_pendatang', ['id' => $pendatang->id]);
    }
}
