<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use App\Models\PendudukPindah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PendudukPindahController extends Controller
{
    /**
     * Display a listing of mutasi pindah.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $pindahList = PendudukPindah::query()
            ->with(['penduduk.desa'])
            ->when($search, function ($query, $search) {
                $query->where('kode_pindah', 'like', "%{$search}%")
                    ->orWhereHas('penduduk', function ($q) use ($search) {
                        $q->where('nama_lengkap', 'like', "%{$search}%")
                            ->orWhere('nik', 'like', "%{$search}%");
                    });
            })
            ->when($bulan, fn ($q) => $q->whereMonth('tanggal_pindah', $bulan))
            ->when($tahun, fn ($q) => $q->whereYear('tanggal_pindah', $tahun))
            ->latest('tanggal_pindah')
            ->paginate(15)
            ->withQueryString();

        return view('mutasi.pindah.index', compact('pindahList', 'search', 'bulan', 'tahun'));
    }

    /**
     * Show the form for creating a new mutasi pindah.
     */
    public function create(): View
    {
        // Only penduduk with status_keberadaan = 'tetap' can be selected!
        $pendudukTetap = Penduduk::where('status_keberadaan', 'tetap')
            ->with('desa')
            ->orderBy('nama_lengkap')
            ->get();

        $prefix = 'PND-'.date('Ym').'-';
        $lastRecord = PendudukPindah::where('kode_pindah', 'like', "{$prefix}%")
            ->orderBy('kode_pindah', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastRecord) {
            $lastNum = (int) substr($lastRecord->kode_pindah, -4);
            $nextNumber = $lastNum + 1;
        }
        $autoKode = $prefix.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);

        return view('mutasi.pindah.create', compact('pendudukTetap', 'autoKode'));
    }

    /**
     * Store a newly created mutasi pindah in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_pindah' => ['required', 'string', 'max:30', 'unique:penduduk_pindah,kode_pindah'],
            'penduduk_id' => [
                'required',
                Rule::exists('penduduk', 'id')->where(function ($query) {
                    $query->where('status_keberadaan', 'tetap');
                }),
            ],
            'alamat_tujuan' => ['required', 'string'],
            'tanggal_pindah' => ['required', 'date'],
            'alasan_pindah' => ['nullable', 'string'],
        ], [
            'kode_pindah.required' => 'Kode mutasi pindah wajib diisi.',
            'kode_pindah.unique' => 'Kode mutasi sudah digunakan.',
            'penduduk_id.required' => 'Pilih data penduduk yang akan pindah.',
            'penduduk_id.exists' => 'Penduduk yang dipilih tidak valid atau sudah tidak berstatus tetap.',
            'alamat_tujuan.required' => 'Alamat tujuan kepindahan wajib diisi.',
            'tanggal_pindah.required' => 'Tanggal pindah wajib diisi.',
        ]);

        $pindah = PendudukPindah::create($validated);

        return redirect()->route('mutasi.pindah.show', $pindah)
            ->with('success', 'Data mutasi pindah berhasil dicatat. Status penduduk otomatis diperbarui menjadi "Pindah".');
    }

    /**
     * Display the specified mutasi pindah.
     */
    public function show(PendudukPindah $pindah): View
    {
        $pindah->load(['penduduk.desa']);

        return view('mutasi.pindah.show', compact('pindah'));
    }

    /**
     * Remove the specified mutasi pindah from storage.
     */
    public function destroy(PendudukPindah $pindah): RedirectResponse
    {
        $kode = $pindah->kode_pindah;
        $pindah->delete();

        return redirect()->route('mutasi.pindah.index')
            ->with('success', 'Data mutasi pindah "'.$kode.'" berhasil dihapus. Status penduduk dipulihkan menjadi "Tetap".');
    }
}
