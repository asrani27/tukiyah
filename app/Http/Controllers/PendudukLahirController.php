<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Penduduk;
use App\Models\PendudukLahir;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PendudukLahirController extends Controller
{
    /**
     * Display a listing of penduduk lahir.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $desaId = $request->input('desa_id');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $desas = Desa::orderBy('nama_desa')->get();

        $kelahiran = PendudukLahir::query()
            ->with('desa')
            ->when($desaId, fn ($q) => $q->where('desa_id', $desaId))
            ->when($bulan, fn ($q) => $q->whereMonth('tanggal_lahir', $bulan))
            ->when($tahun, fn ($q) => $q->whereYear('tanggal_lahir', $tahun))
            ->when($search, function ($query, $search) {
                $query->where('kode_kelahiran', 'like', "%{$search}%")
                    ->orWhere('nama_bayi', 'like', "%{$search}%")
                    ->orWhere('nik_ibu', 'like', "%{$search}%")
                    ->orWhere('nik_ayah', 'like', "%{$search}%");
            })
            ->latest('tanggal_lahir')
            ->paginate(15)
            ->withQueryString();

        return view('mutasi.lahir.index', compact('kelahiran', 'desas', 'search', 'desaId', 'bulan', 'tahun'));
    }

    /**
     * Show the form for creating a new kelahiran record.
     */
    public function create(): View
    {
        $desas = Desa::orderBy('nama_desa')->get();

        [$ibu, $ibuData] = $this->ibuOptions();

        $prefix = 'LHR-'.date('Ym').'-';
        $lastRecord = PendudukLahir::where('kode_kelahiran', 'like', "{$prefix}%")
            ->orderBy('kode_kelahiran', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastRecord) {
            $lastNum = (int) substr($lastRecord->kode_kelahiran, -4);
            $nextNumber = $lastNum + 1;
        }
        $autoKode = $prefix.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);

        return view('mutasi.lahir.create', compact('desas', 'autoKode', 'ibu', 'ibuData'));
    }

    /**
     * Store a newly created kelahiran record in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_kelahiran' => ['required', 'string', 'max:30', 'unique:penduduk_lahir,kode_kelahiran'],
            'desa_id' => ['required', 'exists:desa,id'],
            'nik_ibu' => ['nullable', 'string', 'size:16', 'regex:/^[0-9]{16}$/'],
            'nik_ayah' => ['nullable', 'string', 'size:16', 'regex:/^[0-9]{16}$/'],
            'nama_ayah' => ['nullable', 'string', 'max:100'],
            'nama_bayi' => ['required', 'string', 'max:100'],
            'tempat_lahir' => ['required', 'string', 'max:50'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
        ], [
            'kode_kelahiran.required' => 'Kode kelahiran wajib diisi.',
            'kode_kelahiran.unique' => 'Kode kelahiran sudah terdaftar.',
            'desa_id.required' => 'Pilih salah satu desa.',
            'nama_bayi.required' => 'Nama bayi wajib diisi.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'jenis_kelamin.required' => 'Pilih jenis kelamin.',
        ]);

        $kelahiran = PendudukLahir::create($validated);

        return redirect()->route('mutasi.lahir.index')
            ->with('success', 'Data pencatatan kelahiran "'.$kelahiran->nama_bayi.'" berhasil disimpan.');
    }

    /**
     * Show the form for editing the specified kelahiran record.
     */
    public function edit(PendudukLahir $lahir): View
    {
        $desas = Desa::orderBy('nama_desa')->get();
        [$ibu, $ibuData] = $this->ibuOptions();

        return view('mutasi.lahir.edit', compact('lahir', 'desas', 'ibu', 'ibuData'));
    }

    /**
     * Update the specified kelahiran record in storage.
     */
    public function update(Request $request, PendudukLahir $lahir): RedirectResponse
    {
        $validated = $request->validate([
            'kode_kelahiran' => ['required', 'string', 'max:30', Rule::unique('penduduk_lahir')->ignore($lahir->id)],
            'desa_id' => ['required', 'exists:desa,id'],
            'nik_ibu' => ['nullable', 'string', 'size:16', 'regex:/^[0-9]{16}$/'],
            'nik_ayah' => ['nullable', 'string', 'size:16', 'regex:/^[0-9]{16}$/'],
            'nama_ayah' => ['nullable', 'string', 'max:100'],
            'nama_bayi' => ['required', 'string', 'max:100'],
            'tempat_lahir' => ['required', 'string', 'max:50'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
        ], [
            'kode_kelahiran.required' => 'Kode kelahiran wajib diisi.',
            'nama_bayi.required' => 'Nama bayi wajib diisi.',
        ]);

        $lahir->update($validated);

        return redirect()->route('mutasi.lahir.index')
            ->with('success', 'Data pencatatan kelahiran "'.$lahir->nama_bayi.'" berhasil diperbarui.');
    }

    /**
     * Remove the specified kelahiran record from storage.
     */
    public function destroy(PendudukLahir $lahir): RedirectResponse
    {
        $nama = $lahir->nama_bayi;
        $lahir->delete();

        return redirect()->route('mutasi.lahir.index')
            ->with('success', 'Data kelahiran "'.$nama.'" berhasil dihapus.');
    }

    /**
     * Load daftar ibu (penduduk perempuan berstatus tetap) beserta data info-nya.
     *
     * @return array{0: Collection, 1: Collection}
     */
    private function ibuOptions(): array
    {
        $ibu = Penduduk::tetap()
            ->where('jenis_kelamin', 'P')
            ->orderBy('nama_lengkap')
            ->get(['nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'agama', 'status_perkawinan', 'pendidikan_terakhir', 'alamat']);

        $ibuData = $ibu->mapWithKeys(fn (Penduduk $p) => [$p->nik => [
            'nama_lengkap' => $p->nama_lengkap,
            'tempat_lahir' => $p->tempat_lahir,
            'tanggal_lahir' => $p->tanggal_lahir?->format('d-m-Y'),
            'agama' => $p->agama,
            'status_perkawinan' => $p->status_perkawinan,
            'pendidikan_terakhir' => $p->pendidikan_terakhir,
            'alamat' => $p->alamat,
        ]]);

        return [$ibu, $ibuData];
    }
}
