@extends('layouts.app')

@section('title', 'Detail Mutasi Pindah')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Detail Mutasi Pindah Penduduk</h1>
            <p class="text-xs text-slate-500 mt-1">Kode Mutasi: <span class="font-mono font-semibold text-slate-800">{{ $pindah->kode_pindah }}</span></p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('mutasi.pindah.index') }}" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50">Kembali</a>
            <form action="{{ route('mutasi.pindah.destroy', $pindah) }}" method="POST" onsubmit="return confirm('Hapus data mutasi ini dan kembalikan status penduduk menjadi Tetap?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold">Hapus Mutasi</button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-6 text-xs">
        <div>
            <h2 class="text-xs uppercase font-bold text-slate-400 tracking-wider mb-3">Informasi Penduduk</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-lg border border-slate-200">
                <div>
                    <span class="text-slate-500 block">Nama Lengkap:</span>
                    <span class="font-semibold text-slate-800 text-sm">{{ $pindah->penduduk->nama_lengkap ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block">NIK:</span>
                    <span class="font-mono font-semibold text-slate-800">{{ $pindah->penduduk->nik ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block">Desa Asal:</span>
                    <span class="text-slate-800 font-medium">{{ $pindah->penduduk->desa->nama_desa ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block">Jenis Kelamin:</span>
                    <span class="text-slate-800 font-medium">{{ $pindah->penduduk->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</span>
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-xs uppercase font-bold text-slate-400 tracking-wider mb-3">Rincian Kepindahan</h2>
            <div class="space-y-3">
                <div class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500">Tanggal Pindah:</span>
                    <span class="font-semibold text-slate-800">{{ $pindah->tanggal_pindah->format('d F Y') }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500">Alasan Pindah:</span>
                    <span class="font-semibold text-slate-800">{{ $pindah->alasan_pindah ?? '-' }}</span>
                </div>
                <div class="py-2">
                    <span class="text-slate-500 block mb-1">Alamat Tujuan:</span>
                    <p class="font-medium text-slate-800 bg-slate-50 p-3 rounded-lg border border-slate-200">{{ $pindah->alamat_tujuan }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
