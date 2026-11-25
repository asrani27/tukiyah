<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Penduduk;
use App\Models\PendudukPendatang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PendudukPendatangController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $desaId = $request->input('desa_id');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $desas = Desa::orderBy('nama_desa')->get();

        $pendatang = PendudukPendatang::query()
            ->with('desa')
            ->when($desaId, fn ($q) => $q->where('desa_id', $desaId))
            ->when($bulan, fn ($q) => $q->whereMonth('tanggal_datang', $bulan))
            ->when($tahun, fn ($q) => $q->whereYear('tanggal_datang', $tahun))
            ->when($search, function ($query, $search) {
                $query->where('nik', 'like', "%{$search}%")
                    ->orWhere('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('alamat_asal', 'like', "%{$search}%");
            })
            ->latest('tanggal_datang')
            ->paginate(15)
            ->withQueryString();

        return view('mutasi.pendatang.index', compact('pendatang', 'desas', 'search', 'desaId', 'bulan', 'tahun'));
    }

    public function create(): View
    {
        $desas = Desa::orderBy('nama_desa')->get();

        return view('mutasi.pendatang.create', compact('desas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nik' => ['required', 'string', 'size:16', 'regex:/^[0-9]{16}$/', 'unique:penduduk_pendatang,nik'],
            'desa_id' => ['required', 'exists:desa,id'],
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'tempat_lahir' => ['required', 'string', 'max:50'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'agama' => ['required', 'string', 'max:20'],
            'status_perkawinan' => ['required', 'string', 'max:30'],
            'pendidikan_terakhir' => ['nullable', 'string', 'max:30'],
            'alamat_asal' => ['required', 'string'],
            'tanggal_datang' => ['required', 'date'],
            'telp' => ['nullable', 'string', 'max:20'],
        ]);

        $item = PendudukPendatang::create($validated);

        return redirect()->route('mutasi.pendatang.index')
            ->with('success', 'Data penduduk pendatang "'.$item->nama_lengkap.'" berhasil disimpan.');
    }

    public function edit(PendudukPendatang $pendatang): View
    {
        $desas = Desa::orderBy('nama_desa')->get();

        return view('mutasi.pendatang.edit', compact('pendatang', 'desas'));
    }

    public function update(Request $request, PendudukPendatang $pendatang): RedirectResponse
    {
        $validated = $request->validate([
            'nik' => ['required', 'string', 'size:16', 'regex:/^[0-9]{16}$/', Rule::unique('penduduk_pendatang')->ignore($pendatang->id)],
            'desa_id' => ['required', 'exists:desa,id'],
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'tempat_lahir' => ['required', 'string', 'max:50'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'agama' => ['required', 'string', 'max:20'],
            'status_perkawinan' => ['required', 'string', 'max:30'],
            'pendidikan_terakhir' => ['nullable', 'string', 'max:30'],
            'alamat_asal' => ['required', 'string'],
            'tanggal_datang' => ['required', 'date'],
            'telp' => ['nullable', 'string', 'max:20'],
        ]);

        $pendatang->update($validated);

        return redirect()->route('mutasi.pendatang.index')
            ->with('success', 'Data penduduk pendatang "'.$pendatang->nama_lengkap.'" berhasil diperbarui.');
    }

    public function destroy(PendudukPendatang $pendatang): RedirectResponse
    {
        $nama = $pendatang->nama_lengkap;
        $pendatang->delete();

        return redirect()->route('mutasi.pendatang.index')
            ->with('success', 'Data pendatang "'.$nama.'" berhasil dihapus.');
    }

    public function jadikanTetap(PendudukPendatang $pendatang): RedirectResponse
    {
        if (Penduduk::where('nik', $pendatang->nik)->exists()) {
            return redirect()->back()
                ->with('error', 'Penduduk dengan NIK '.$pendatang->nik.' sudah terdaftar di Data Penduduk Tetap.');
        }

        Penduduk::create([
            'nik' => $pendatang->nik,
            'desa_id' => $pendatang->desa_id,
            'nama_lengkap' => $pendatang->nama_lengkap,
            'tempat_lahir' => $pendatang->tempat_lahir,
            'tanggal_lahir' => $pendatang->tanggal_lahir,
            'jenis_kelamin' => $pendatang->jenis_kelamin,
            'agama' => $pendatang->agama,
            'status_perkawinan' => $pendatang->status_perkawinan ?? 'Kawin',
            'pendidikan_terakhir' => $pendatang->pendidikan_terakhir,
            'alamat' => 'Desa '.$pendatang->desa->nama_desa.' (Asal: '.$pendatang->alamat_asal.')',
            'telp' => $pendatang->telp,
            'status_keberadaan' => 'tetap',
        ]);

        return redirect()->route('penduduk.index')
            ->with('success', 'Penduduk pendatang "'.$pendatang->nama_lengkap.'" berhasil didaftarkan sebagai Penduduk Tetap!');
    }
}
