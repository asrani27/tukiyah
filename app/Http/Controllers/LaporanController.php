<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Penduduk;
use App\Models\PendudukLahir;
use App\Models\PendudukMeninggal;
use App\Models\PendudukPendatang;
use App\Models\PendudukPindah;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $jenis = $request->input('jenis', 'penduduk_tetap');
        $desaId = $request->input('desa_id', 'all');
        $bulan = $request->input('bulan', 'all');
        $tahun = $request->input('tahun', (string) Carbon::now()->year);

        $desas = Desa::orderBy('nama_desa')->get();
        $data = $this->getReportData($jenis, $desaId, $bulan, $tahun);

        return view('laporan.index', compact('jenis', 'desaId', 'bulan', 'tahun', 'desas', 'data'));
    }

    public function cetakPdf(Request $request): Response
    {
        $jenis = $request->input('jenis', 'penduduk_tetap');
        $desaId = $request->input('desa_id', 'all');
        $bulan = $request->input('bulan', 'all');
        $tahun = $request->input('tahun', (string) Carbon::now()->year);

        $desaObj = ($desaId !== 'all') ? Desa::find($desaId) : null;
        $data = $this->getReportData($jenis, $desaId, $bulan, $tahun);

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $periodeText = '';
        if ($bulan !== 'all' && isset($namaBulan[(int) $bulan])) {
            $periodeText .= $namaBulan[(int) $bulan].' ';
        }
        if ($tahun !== 'all') {
            $periodeText .= $tahun;
        }
        if (empty(trim($periodeText))) {
            $periodeText = 'Semua Periode';
        }

        $judulLaporan = match ($jenis) {
            'penduduk_tetap' => 'LAPORAN DATA PENDUDUK TETAP',
            'penduduk_pindah' => 'LAPORAN MUTASI PENDUDUK PINDAH',
            'penduduk_meninggal' => 'LAPORAN MUTASI PENDUDUK MENINGGAL (KEMATIAN)',
            'penduduk_lahir' => 'LAPORAN PENCATATAN KELAHIRAN PENDUDUK',
            'penduduk_pendatang' => 'LAPORAN PENDUDUK PENDATANG',
            default => 'LAPORAN KEPENDUDUKAN',
        };

        $viewName = match ($jenis) {
            'penduduk_tetap' => 'laporan.pdf.penduduk',
            'penduduk_pindah' => 'laporan.pdf.pindah',
            'penduduk_meninggal' => 'laporan.pdf.meninggal',
            'penduduk_lahir' => 'laporan.pdf.lahir',
            'penduduk_pendatang' => 'laporan.pdf.pendatang',
            default => 'laporan.pdf.penduduk',
        };

        $pdf = Pdf::loadView($viewName, [
            'data' => $data,
            'judulLaporan' => $judulLaporan,
            'periodeText' => $periodeText,
            'desa' => $desaObj,
            'tanggalCetak' => Carbon::now()->translatedFormat('d F Y'),
        ]);

        $pdf->setPaper('a4', 'landscape');
        $fileName = strtolower(str_replace(' ', '_', $judulLaporan)).'_'.date('YmdHis').'.pdf';

        return $pdf->stream($fileName);
    }

    protected function getReportData(string $jenis, string $desaId, string $bulan, string $tahun)
    {
        return match ($jenis) {
            'penduduk_tetap' => Penduduk::query()
                ->where('status_keberadaan', 'tetap')
                ->with('desa')
                ->when($desaId !== 'all', fn ($q) => $q->where('desa_id', $desaId))
                ->when($bulan !== 'all', fn ($q) => $q->whereMonth('created_at', $bulan))
                ->when($tahun !== 'all', fn ($q) => $q->whereYear('created_at', $tahun))
                ->orderBy('nama_lengkap')
                ->get(),

            'penduduk_pindah' => PendudukPindah::query()
                ->with(['penduduk.desa'])
                ->when($desaId !== 'all', fn ($q) => $q->whereHas('penduduk', fn ($sq) => $sq->where('desa_id', $desaId)))
                ->when($bulan !== 'all', fn ($q) => $q->whereMonth('tanggal_pindah', $bulan))
                ->when($tahun !== 'all', fn ($q) => $q->whereYear('tanggal_pindah', $tahun))
                ->orderBy('tanggal_pindah', 'desc')
                ->get(),

            'penduduk_meninggal' => PendudukMeninggal::query()
                ->with(['penduduk.desa'])
                ->when($desaId !== 'all', fn ($q) => $q->whereHas('penduduk', fn ($sq) => $sq->where('desa_id', $desaId)))
                ->when($bulan !== 'all', fn ($q) => $q->whereMonth('tanggal_meninggal', $bulan))
                ->when($tahun !== 'all', fn ($q) => $q->whereYear('tanggal_meninggal', $tahun))
                ->orderBy('tanggal_meninggal', 'desc')
                ->get(),

            'penduduk_lahir' => PendudukLahir::query()
                ->with('desa')
                ->when($desaId !== 'all', fn ($q) => $q->where('desa_id', $desaId))
                ->when($bulan !== 'all', fn ($q) => $q->whereMonth('tanggal_lahir', $bulan))
                ->when($tahun !== 'all', fn ($q) => $q->whereYear('tanggal_lahir', $tahun))
                ->orderBy('tanggal_lahir', 'desc')
                ->get(),

            'penduduk_pendatang' => PendudukPendatang::query()
                ->with('desa')
                ->when($desaId !== 'all', fn ($q) => $q->where('desa_id', $desaId))
                ->when($bulan !== 'all', fn ($q) => $q->whereMonth('tanggal_datang', $bulan))
                ->when($tahun !== 'all', fn ($q) => $q->whereYear('tanggal_datang', $tahun))
                ->orderBy('tanggal_datang', 'desc')
                ->get(),

            default => collect(),
        };
    }
}
