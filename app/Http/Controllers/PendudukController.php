<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Penduduk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PendudukController extends Controller
{
    /**
     * Display a listing of penduduk.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $desaId = $request->input('desa_id');
        $status = $request->input('status', 'tetap');

        $desas = Desa::orderBy('nama_desa')->get();

        $penduduks = Penduduk::query()
            ->with(['desa', 'pindah', 'meninggal'])
            ->when($status !== 'semua', fn ($q) => $q->where('status_keberadaan', $status))
            ->when($desaId, fn ($q, $id) => $q->where('desa_id', $id))
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nik', 'like', "%{$search}%")
                        ->orWhere('nama_lengkap', 'like', "%{$search}%");
                });
            })
            ->orderBy('nama_lengkap')
            ->paginate(15)
            ->withQueryString();

        $penduduk = $penduduks;

        return view('penduduk.index', compact('penduduks', 'penduduk', 'desas', 'search', 'desaId', 'status'));
    }

    /**
     * Show the form for creating a new penduduk.
     */
    public function create(): View
    {
        $desas = Desa::orderBy('nama_desa')->get();

        return view('penduduk.create', compact('desas'));
    }

    /**
     * Store a newly created penduduk in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nik' => ['required', 'string', 'size:16', 'regex:/^[0-9]{16}$/', 'unique:penduduk,nik'],
            'desa_id' => ['required', 'exists:desa,id'],
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'tempat_lahir' => ['required', 'string', 'max:50'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'agama' => ['required', 'string', 'max:20'],
            'status_perkawinan' => ['required', 'string', 'max:30'],
            'pendidikan_terakhir' => ['nullable', 'string', 'max:30'],
            'alamat' => ['required', 'string'],
            'telp' => ['nullable', 'string', 'max:20'],
            'status_keberadaan' => ['nullable', Rule::in(['tetap', 'pindah', 'meninggal'])],
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus 16 digit.',
            'nik.regex' => 'NIK harus berupa 16 digit angka.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'desa_id.required' => 'Pilih salah satu desa.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
        ]);

        $validated['status_keberadaan'] = $validated['status_keberadaan'] ?? 'tetap';

        $penduduk = Penduduk::create($validated);

        return redirect()->route('penduduk.show', $penduduk)
            ->with('success', 'Data Penduduk "'.$penduduk->nama_lengkap.'" berhasil ditambahkan.');
    }

    /**
     * Display the specified penduduk.
     */
    public function show(Penduduk $penduduk): View
    {
        $penduduk->load(['desa', 'pindah', 'meninggal']);

        return view('penduduk.show', compact('penduduk'));
    }

    /**
     * Show the form for editing the specified penduduk.
     */
    public function edit(Penduduk $penduduk): View
    {
        $desas = Desa::orderBy('nama_desa')->get();

        return view('penduduk.edit', compact('penduduk', 'desas'));
    }

    /**
     * Update the specified penduduk in storage.
     */
    public function update(Request $request, Penduduk $penduduk): RedirectResponse
    {
        $validated = $request->validate([
            'nik' => ['required', 'string', 'size:16', 'regex:/^[0-9]{16}$/', Rule::unique('penduduk')->ignore($penduduk->id)],
            'desa_id' => ['required', 'exists:desa,id'],
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'tempat_lahir' => ['required', 'string', 'max:50'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'agama' => ['required', 'string', 'max:20'],
            'status_perkawinan' => ['required', 'string', 'max:30'],
            'pendidikan_terakhir' => ['nullable', 'string', 'max:30'],
            'alamat' => ['required', 'string'],
            'telp' => ['nullable', 'string', 'max:20'],
            'status_keberadaan' => ['required', Rule::in(['tetap', 'pindah', 'meninggal'])],
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus 16 digit.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
        ]);

        $penduduk->update($validated);

        return redirect()->route('penduduk.show', $penduduk)
            ->with('success', 'Data Penduduk "'.$penduduk->nama_lengkap.'" berhasil diperbarui.');
    }

    /**
     * Remove the specified penduduk from storage.
     */
    public function destroy(Penduduk $penduduk): RedirectResponse
    {
        $nama = $penduduk->nama_lengkap;
        $penduduk->delete();

        return redirect()->route('penduduk.index')
            ->with('success', 'Data Penduduk "'.$nama.'" berhasil dihapus.');
    }
}
