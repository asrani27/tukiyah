<?php

namespace Tests\Feature;

use App\Models\Desa;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KependudukanTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Test autentikasi login berhasil dan redirect ke dashboard.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'username' => $this->user->username,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->user);
    }

    /**
     * Test login gagal dengan kredensial salah.
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'username' => $this->user->username,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    /**
     * Test logout berhasil.
     */
    public function test_user_can_logout(): void
    {
        $response = $this->actingAs($this->user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     * Test CRUD Desa: Create, Read, Update, Delete.
     */
    public function test_desa_crud_lifecycle(): void
    {
        // 1. Tampilkan form create desa
        $this->actingAs($this->user)->get('/desa/create')->assertStatus(200);

        // 2. Simpan desa baru
        $desaData = [
            'nama_desa' => 'Bati-Bati Barat',
            'kepala_desa' => 'H. Ahmad Fauzi',
            'telp' => '081234567890',
            'alamat' => 'Jl. Jenderal Sudirman No. 10',
        ];

        $response = $this->actingAs($this->user)->post('/desa', $desaData);
        $response->assertRedirect('/desa');
        $this->assertDatabaseHas('desa', ['nama_desa' => 'Bati-Bati Barat']);

        $desa = Desa::where('nama_desa', 'Bati-Bati Barat')->first();
        $this->assertNotNull($desa);

        // 3. Tampilkan index desa
        $responseIndex = $this->actingAs($this->user)->get('/desa');
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Bati-Bati Barat');

        // 4. Edit form
        $this->actingAs($this->user)->get("/desa/{$desa->id}/edit")->assertStatus(200);

        // 5. Update data desa
        $updateData = [
            'nama_desa' => 'Bati-Bati Indah',
            'kepala_desa' => 'H. Ahmad Fauzi, S.Sos',
            'telp' => '081234567899',
            'alamat' => 'Jl. Jenderal Sudirman No. 12',
        ];
        $responseUpdate = $this->actingAs($this->user)->put("/desa/{$desa->id}", $updateData);
        $responseUpdate->assertRedirect('/desa');
        $this->assertDatabaseHas('desa', ['nama_desa' => 'Bati-Bati Indah']);

        // 6. Hapus desa
        $responseDelete = $this->actingAs($this->user)->delete("/desa/{$desa->id}");
        $responseDelete->assertRedirect('/desa');
        $this->assertDatabaseMissing('desa', ['id' => $desa->id]);
    }

    /**
     * Test validasi saat input desa tidak lengkap atau nama desa duplikat.
     */
    public function test_desa_validation_fails_for_duplicate_or_empty_name(): void
    {
        Desa::create([
            'nama_desa' => 'Padang Luas',
            'kepala_desa' => 'M. Basri',
        ]);

        $response = $this->actingAs($this->user)->post('/desa', [
            'nama_desa' => 'Padang Luas',
            'kepala_desa' => '',
        ]);

        $response->assertSessionHasErrors(['nama_desa', 'kepala_desa']);
    }

    /**
     * Test CRUD Penduduk: Create, Read, Show, Update, Delete.
     */
    public function test_penduduk_crud_lifecycle(): void
    {
        $desa = Desa::create([
            'nama_desa' => 'Nusa Indah',
            'kepala_desa' => 'Syaifuddin',
        ]);

        // 1. Tampilkan form create penduduk
        $this->actingAs($this->user)->get('/penduduk/create')->assertStatus(200);

        // 2. Simpan penduduk baru
        $pendudukData = [
            'nik' => '6301011505900001',
            'desa_id' => $desa->id,
            'nama_lengkap' => 'Muhammad Rizki',
            'tempat_lahir' => 'Pelaihari',
            'tanggal_lahir' => '1990-05-15',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'status_perkawinan' => 'Kawin',
            'pendidikan_terakhir' => 'S1',
            'alamat' => 'Jl. Ahmad Yani RT 02 RW 01',
            'telp' => '085248112233',
            'status_keberadaan' => 'tetap',
        ];

        $response = $this->actingAs($this->user)->post('/penduduk', $pendudukData);
        $penduduk = Penduduk::where('nik', '6301011505900001')->first();
        $this->assertNotNull($penduduk);
        $response->assertRedirect("/penduduk/{$penduduk->id}");

        $this->assertDatabaseHas('penduduk', [
            'nik' => '6301011505900001',
            'status_keberadaan' => 'tetap',
        ]);

        // 3. Lihat show detail penduduk
        $responseShow = $this->actingAs($this->user)->get("/penduduk/{$penduduk->id}");
        $responseShow->assertStatus(200);
        $responseShow->assertSee('Muhammad Rizki');
        $responseShow->assertSee('6301011505900001');

        // 4. Buka form edit
        $this->actingAs($this->user)->get("/penduduk/{$penduduk->id}/edit")->assertStatus(200);

        // 5. Update data penduduk
        $pendudukData['nama_lengkap'] = 'Muhammad Rizki Pratama';
        $responseUpdate = $this->actingAs($this->user)->put("/penduduk/{$penduduk->id}", $pendudukData);
        $responseUpdate->assertRedirect("/penduduk/{$penduduk->id}");
        $this->assertDatabaseHas('penduduk', [
            'id' => $penduduk->id,
            'nama_lengkap' => 'Muhammad Rizki Pratama',
        ]);

        // 6. Hapus data penduduk
        $responseDelete = $this->actingAs($this->user)->delete("/penduduk/{$penduduk->id}");
        $responseDelete->assertRedirect('/penduduk');
        $this->assertDatabaseMissing('penduduk', ['id' => $penduduk->id]);
    }

    /**
     * Test filter dan pencarian penduduk di index.
     */
    public function test_penduduk_filter_and_search(): void
    {
        $desa1 = Desa::create(['nama_desa' => 'Bati-Bati', 'kepala_desa' => 'Kades 1']);
        $desa2 = Desa::create(['nama_desa' => 'Kait-Kait', 'kepala_desa' => 'Kades 2']);

        $p1 = Penduduk::create([
            'nik' => '6301010101010001',
            'desa_id' => $desa1->id,
            'nama_lengkap' => 'Budi Santoso',
            'tempat_lahir' => 'Bati-Bati',
            'tanggal_lahir' => '1985-01-01',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'status_perkawinan' => 'Kawin',
            'alamat' => 'Alamat 1',
            'status_keberadaan' => 'tetap',
        ]);

        $p2 = Penduduk::create([
            'nik' => '6301010202020002',
            'desa_id' => $desa2->id,
            'nama_lengkap' => 'Siti Aminah',
            'tempat_lahir' => 'Kait-Kait',
            'tanggal_lahir' => '1992-02-02',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'status_perkawinan' => 'Kawin',
            'alamat' => 'Alamat 2',
            'status_keberadaan' => 'tetap',
        ]);

        // Filter berdasarkan desa 1
        $responseDesa = $this->actingAs($this->user)->get("/penduduk?desa_id={$desa1->id}");
        $responseDesa->assertStatus(200);
        $responseDesa->assertSee('Budi Santoso');
        $responseDesa->assertDontSee('Siti Aminah');

        // Pencarian nama
        $responseSearch = $this->actingAs($this->user)->get('/penduduk?search=Aminah');
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('Siti Aminah');
        $responseSearch->assertDontSee('Budi Santoso');
    }
}
