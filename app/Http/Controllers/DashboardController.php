<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Penduduk;
use App\Models\PendudukLahir;
use App\Models\PendudukMeninggal;
use App\Models\PendudukPendatang;
use App\Models\PendudukPindah;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the application dashboard.
     */
    public function index(): View
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $totalPendudukTetap = Penduduk::where('status_keberadaan', 'tetap')->count();
        $totalLakiLaki = Penduduk::where('status_keberadaan', 'tetap')->where('jenis_kelamin', 'L')->count();
        $totalPerempuan = Penduduk::where('status_keberadaan', 'tetap')->where('jenis_kelamin', 'P')->count();

        $kelahiranBulanIni = PendudukLahir::whereMonth('tanggal_lahir', $currentMonth)
            ->whereYear('tanggal_lahir', $currentYear)
            ->count();
        $totalKelahiran = PendudukLahir::count();

        $kematianBulanIni = PendudukMeninggal::whereMonth('tanggal_meninggal', $currentMonth)
            ->whereYear('tanggal_meninggal', $currentYear)
            ->count();
        $totalKematian = PendudukMeninggal::count();

        $pindahBulanIni = PendudukPindah::whereMonth('tanggal_pindah', $currentMonth)
            ->whereYear('tanggal_pindah', $currentYear)
            ->count();
        $totalPindah = PendudukPindah::count();

        $pendatangBulanIni = PendudukPendatang::whereMonth('tanggal_datang', $currentMonth)
            ->whereYear('tanggal_datang', $currentYear)
            ->count();
        $totalPendatang = PendudukPendatang::count();

        $desaStats = Desa::withCount(['pendudukTetap'])
            ->orderBy('nama_desa')
            ->get();

        $recentPindah = PendudukPindah::with(['penduduk.desa'])
            ->latest('tanggal_pindah')
            ->take(5)
            ->get();

        $recentMeninggal = PendudukMeninggal::with(['penduduk.desa'])
            ->latest('tanggal_meninggal')
            ->take(5)
            ->get();

        $recentLahir = PendudukLahir::with('desa')
            ->latest('tanggal_lahir')
            ->take(5)
            ->get();

        $recentPendatang = PendudukPendatang::with('desa')
            ->latest('tanggal_datang')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalPendudukTetap',
            'totalLakiLaki',
            'totalPerempuan',
            'kelahiranBulanIni',
            'totalKelahiran',
            'kematianBulanIni',
            'totalKematian',
            'pindahBulanIni',
            'totalPindah',
            'pendatangBulanIni',
            'totalPendatang',
            'desaStats',
            'recentPindah',
            'recentMeninggal',
            'recentLahir',
            'recentPendatang'
        ));
    }
}
