<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DesaController extends Controller
{
    /**
     * Display a listing of desa.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $desas = Desa::query()
            ->withCount(['pendudukTetap', 'pendudukLahir', 'pendudukPendatang'])
            ->when($search, function ($query, $search) {
                $query->where('nama_desa', 'like', "%{$search}%")
                    ->orWhere('kepala_desa', 'like', "%{$search}%");
            })
            ->orderBy('nama_desa')
            ->paginate(10)
            ->withQueryString();

        return view('desa.index', compact('desas', 'search'));
    }

    /**
     * Show the form for creating a new desa.
     */
    public function create(): View
    {
        return view('desa.create');
    }

    /**
     * Store a newly created desa in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_desa' => ['required', 'string', 'max:50', 'unique:desa,nama_desa'],
            'kepala_desa' => ['required', 'string', 'max:100'],
            'telp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
        ], [
            'nama_desa.required' => 'Nama desa wajib diisi.',
            'nama_desa.unique' => 'Nama desa sudah terdaftar.',
            'kepala_desa.required' => 'Nama kepala desa wajib diisi.',
        ]);

        Desa::create($validated);

        return redirect()->route('desa.index')
            ->with('success', 'Data Desa "'.$validated['nama_desa'].'" berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified desa.
     */
    public function edit(Desa $desa): View
    {
        return view('desa.edit', compact('desa'));
    }

    /**
     * Update the specified desa in storage.
     */
    public function update(Request $request, Desa $desa): RedirectResponse
    {
        $validated = $request->validate([
            'nama_desa' => ['required', 'string', 'max:50', 'unique:desa,nama_desa,'.$desa->id],
            'kepala_desa' => ['required', 'string', 'max:100'],
            'telp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
        ], [
            'nama_desa.required' => 'Nama desa wajib diisi.',
            'nama_desa.unique' => 'Nama desa sudah terdaftar.',
            'kepala_desa.required' => 'Nama kepala desa wajib diisi.',
        ]);

        $desa->update($validated);

        return redirect()->route('desa.index')
            ->with('success', 'Data Desa "'.$desa->nama_desa.'" berhasil diperbarui.');
    }

    /**
     * Remove the specified desa from storage.
     */
    public function destroy(Desa $desa): RedirectResponse
    {
        $namaDesa = $desa->nama_desa;
        $desa->delete();

        return redirect()->route('desa.index')
            ->with('success', 'Data Desa "'.$namaDesa.'" beserta relasinya berhasil dihapus.');
    }
}
