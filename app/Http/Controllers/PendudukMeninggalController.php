<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use App\Models\PendudukMeninggal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PendudukMeninggalController extends Controller
{
    /**
     * Display a listing of mutasi kematian.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $meninggalList = PendudukMeninggal::query()
            ->with(['penduduk.desa'])
            ->when($search, function ($query, $search) {
                $query->where('kode_kematian', 'like', "%{$search}%")
                    ->orWhereHas('penduduk', function ($q) use ($search) {
                        $q->where('nama_lengkap', 'like', "%{$search}%")
                            ->orWhere('nik', 'like', "%{$search}%");
                    });
            })
            ->when($bulan, fn ($q) => $q->whereMonth('tanggal_meninggal', $bulan))
            ->when($tahun, fn ($q) => $q->whereYear('tanggal_meninggal', $tahun))
            ->latest('tanggal_meninggal')
            ->paginate(15)
            ->withQueryString();

        return view('mutasi.meninggal.index', compact('meninggalList', 'search', 'bulan', 'tahun'));
    }

    /**
     * Show the form for creating a new mutasi kematian.
     */
    public function create(): View
    {
        // Only penduduk with status_keberadaan = 'tetap' can be selected!
        $pendudukTetap = Penduduk::where('status_keberadaan', 'tetap')
            ->with('desa')
            ->orderBy('nama_lengkap')
            ->get();

        $prefix = 'KMT-'.date('Ym').'-';
        $lastRecord = PendudukMeninggal::where('kode_kematian', 'like', "{$prefix}%")
            ->orderBy('kode_kematian', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastRecord) {
            $lastNum = (int) substr($lastRecord->kode_kematian, -4);
            $nextNumber = $lastNum + 1;
        }
        $autoKode = $prefix.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);

        return view('mutasi.meninggal.create', compact('pendudukTetap', 'autoKode'));
    }

    /**
     * Store a newly created mutasi kematian in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_kematian' => ['required', 'string', 'max:30', 'unique:penduduk_meninggal,kode_kematian'],
            'penduduk_id' => [
                'required',
                Rule::exists('penduduk', 'id')->where(function ($query) {
                    $query->where('status_keberadaan', 'tetap');
                }),
            ],
            'tanggal_meninggal' => ['required', 'date'],
            'tempat_meninggal' => ['required', 'string', 'max:100'],
            'sebab_meninggal' => ['nullable', 'string', 'max:100'],
        ], [
            'kode_kematian.required' => 'Kode mutasi kematian wajib diisi.',
            'kode_kematian.unique' => 'Kode kematian sudah terdaftar.',
            'penduduk_id.required' => 'Pilih data penduduk yang meninggal.',
            'penduduk_id.exists' => 'Penduduk yang dipilih tidak valid atau sudah tidak berstatus tetap.',
            'tanggal_meninggal.required' => 'Tanggal meninggal wajib diisi.',
            'tempat_meninggal.required' => 'Tempat meninggal wajib diisi.',
        ]);

        $meninggal = PendudukMeninggal::create($validated);

        return redirect()->route('mutasi.meninggal.show', $meninggal)
            ->with('success', 'Data kematian berhasil dicatat. Status penduduk otomatis diperbarui menjadi "Meninggal".');
    }

    /**
     * Display the specified mutasi kematian.
     */
    public function show(PendudukMeninggal $meninggal): View
    {
        $meninggal->load(['penduduk.desa']);

        return view('mutasi.meninggal.show', compact('meninggal'));
    }

    /**
     * Remove the specified mutasi kematian from storage.
     */
    public function destroy(PendudukMeninggal $meninggal): RedirectResponse
    {
        $kode = $meninggal->kode_kematian;
        $meninggal->delete();

        return redirect()->route('mutasi.meninggal.index')
            ->with('success', 'Data kematian "'.$kode.'" berhasil dihapus. Status penduduk dipulihkan menjadi "Tetap".');
    }
}
