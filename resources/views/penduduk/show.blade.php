@extends('layouts.app')

@section('title', 'Detail Penduduk - ' . $penduduk->nama_lengkap)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Biodata Penduduk</h1>
            <p class="text-xs text-slate-500 mt-1">Detail data kependudukan perorangan warga Kecamatan Bati-Bati.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('penduduk.index') }}" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50">Kembali</a>
            <a href="{{ route('penduduk.edit', $penduduk) }}" class="px-3 py-1.5 rounded-lg bg-amber-500 text-slate-950 text-xs font-semibold hover:bg-amber-600">Edit Data</a>
        </div>
    </div>

    <!-- Main Profile Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-4">
            <div>
                <span class="text-xs font-semibold uppercase text-slate-400">Nama Lengkap</span>
                <h2 class="text-2xl font-bold text-slate-900">{{ $penduduk->nama_lengkap }}</h2>
                <div class="text-xs text-slate-500 font-mono mt-0.5">NIK: {{ $penduduk->nik }} &bull; No KK: {{ $penduduk->no_kk }}</div>
            </div>
            <div>
                @if($penduduk->status_keberadaan == 'tetap')
                    <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">Warga Tetap</span>
                @elseif($penduduk->status_keberadaan == 'pindah')
                    <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">Sudah Pindah</span>
                @else
                    <span class="px-3 py-1 rounded-full bg-rose-100 text-rose-800 text-xs font-bold">Meninggal Dunia</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 text-xs">
            <div class="space-y-3">
                <h3 class="font-bold text-slate-800 uppercase tracking-wider pb-1 border-b border-slate-100">Informasi Pribadi</h3>
                <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-slate-500">Jenis Kelamin:</span><span class="font-semibold text-slate-800">{{ $penduduk->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</span></div>
                <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-slate-500">Tempat, Tanggal Lahir:</span><span class="font-semibold text-slate-800">{{ $penduduk->tempat_lahir }}, {{ $penduduk->tanggal_lahir ? $penduduk->tanggal_lahir->format('d F Y') : '-' }}</span></div>
                <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-slate-500">Agama:</span><span class="font-semibold text-slate-800">{{ $penduduk->agama }}</span></div>
                <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-slate-500">Golongan Darah:</span><span class="font-semibold text-slate-800">{{ $penduduk->golongan_darah }}</span></div>
                <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-slate-500">Status Perkawinan:</span><span class="font-semibold text-slate-800">{{ $penduduk->status_perkawinan }}</span></div>
                <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-slate-500">Hubungan Keluarga:</span><span class="font-semibold text-slate-800">{{ $penduduk->status_keluarga }}</span></div>
            </div>

            <div class="space-y-3">
                <h3 class="font-bold text-slate-800 uppercase tracking-wider pb-1 border-b border-slate-100">Domisili & Sosial</h3>
                <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-slate-500">Pendidikan Terakhir:</span><span class="font-semibold text-slate-800">{{ $penduduk->pendidikan_terakhir }}</span></div>
                <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-slate-500">Pekerjaan:</span><span class="font-semibold text-slate-800">{{ $penduduk->pekerjaan }}</span></div>
                <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-slate-500">Kewarganegaraan:</span><span class="font-semibold text-slate-800">{{ $penduduk->kewarganegaraan }}</span></div>
                <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-slate-500">Desa / Kelurahan:</span><span class="font-semibold text-slate-800">{{ $penduduk->desa->nama_desa ?? '-' }}</span></div>
                <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-slate-500">Alamat Lengkap:</span><span class="font-semibold text-slate-800 text-right">{{ $penduduk->alamat }} RT {{ $penduduk->rt }} / RW {{ $penduduk->rw }}</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
