@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-emerald-800 to-teal-900 rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-700/80 border border-emerald-500/50 text-emerald-200 uppercase tracking-wider mb-2">
                Sistem Informasi Kependudukan
            </span>
            <h1 class="text-2xl font-bold tracking-tight">Kecamatan Bati-Bati, Kab. Tanah Laut</h1>
            <p class="text-sm text-emerald-100 mt-1">Ringkasan data penduduk dan statistik mutasi kependudukan terkini.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('laporan.index') }}" class="px-3.5 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-white text-xs font-semibold backdrop-blur transition border border-white/20">Cetak Laporan</a>
            <a href="{{ route('penduduk.create') }}" class="px-3.5 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-semibold transition shadow-md">Tambah Penduduk</a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold uppercase text-slate-500">Penduduk Tetap</span>
            <div class="text-2xl font-bold text-slate-900 mt-2">{{ number_format($totalPendudukTetap) }}</div>
            <div class="text-xs text-slate-500 mt-1">L: <strong class="text-blue-600">{{ $totalLakiLaki }}</strong> &bull; P: <strong class="text-rose-600">{{ $totalPerempuan }}</strong></div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold uppercase text-slate-500">Kelahiran Bulan Ini</span>
            <div class="text-2xl font-bold text-slate-900 mt-2">{{ number_format($kelahiranBulanIni) }}</div>
            <div class="text-xs text-slate-500 mt-1">Total: {{ number_format($totalKelahiran) }}</div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold uppercase text-slate-500">Kematian Bulan Ini</span>
            <div class="text-2xl font-bold text-slate-900 mt-2">{{ number_format($kematianBulanIni) }}</div>
            <div class="text-xs text-slate-500 mt-1">Total: {{ number_format($totalKematian) }}</div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold uppercase text-slate-500">Mutasi Pindah</span>
            <div class="text-2xl font-bold text-slate-900 mt-2">{{ number_format($pindahBulanIni) }}</div>
            <div class="text-xs text-slate-500 mt-1">Total: {{ number_format($totalPindah) }}</div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold uppercase text-slate-500">Pendatang</span>
            <div class="text-2xl font-bold text-slate-900 mt-2">{{ number_format($pendatangBulanIni) }}</div>
            <div class="text-xs text-slate-500 mt-1">Total: {{ number_format($totalPendatang) }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h2 class="text-sm font-bold text-slate-800">Distribusi per Desa</h2>
                <a href="{{ route('desa.index') }}" class="text-xs text-emerald-700 font-semibold hover:underline">Kelola Desa</a>
            </div>
            <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto mt-2">
                @forelse($desaStats as $desa)
                    <div class="py-2.5 flex items-center justify-between text-xs">
                        <span class="font-medium text-slate-700">{{ $desa->nama_desa }}</span>
                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold border border-emerald-200">{{ $desa->penduduk_tetap_count }} Jiwa</span>
                    </div>
                @empty
                    <div class="text-xs text-slate-400 py-3 text-center">Belum ada desa.</div>
                @endforelse
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <h2 class="text-sm font-bold text-slate-800 pb-3 border-b border-slate-100">Mutasi Terkini</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                <div class="bg-slate-50 rounded-lg p-3 border border-slate-200/80">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-800">Pindah Terkini</span>
                        <a href="{{ route('mutasi.pindah.index') }}" class="text-[11px] text-amber-700 font-semibold hover:underline">Semua</a>
                    </div>
                    <div class="space-y-2">
                        @forelse($recentPindah as $p)
                            <div class="bg-white p-2 rounded border border-slate-200/60 text-xs">
                                <div class="font-semibold text-slate-800">{{ $p->penduduk->nama_lengkap ?? '-' }}</div>
                                <div class="text-[11px] text-slate-500 truncate">Tujuan: {{ $p->alamat_tujuan }}</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">Tgl: {{ $p->tanggal_pindah->format('d/m/Y') }}</div>
                            </div>
                        @empty
                            <div class="text-[11px] text-slate-400 py-2 text-center">Belum ada mutasi pindah.</div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-slate-50 rounded-lg p-3 border border-slate-200/80">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-800">Kematian Terkini</span>
                        <a href="{{ route('mutasi.meninggal.index') }}" class="text-[11px] text-rose-700 font-semibold hover:underline">Semua</a>
                    </div>
                    <div class="space-y-2">
                        @forelse($recentMeninggal as $m)
                            <div class="bg-white p-2 rounded border border-slate-200/60 text-xs">
                                <div class="font-semibold text-slate-800">{{ $m->penduduk->nama_lengkap ?? '-' }}</div>
                                <div class="text-[11px] text-slate-500 truncate">Tempat: {{ $m->tempat_meninggal }}</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">Tgl: {{ $m->tanggal_meninggal->format('d/m/Y') }}</div>
                            </div>
                        @empty
                            <div class="text-[11px] text-slate-400 py-2 text-center">Belum ada kematian.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
