@extends('layouts.app')

@section('title', 'Detail Mutasi Kematian')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Detail Mutasi Kematian Penduduk</h1>
            <p class="text-xs text-slate-500 mt-1">Kode: <span class="font-mono font-semibold text-slate-800">{{ $meninggal->kode_kematian }}</span></p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('mutasi.meninggal.index') }}" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50">Kembali</a>
            <form action="{{ route('mutasi.meninggal.destroy', $meninggal) }}" method="POST" onsubmit="return confirm('Hapus data kematian ini dan pulihkan status penduduk menjadi Tetap?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold">Hapus Data</button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-6 text-xs">
        <div>
            <h2 class="text-xs uppercase font-bold text-slate-400 tracking-wider mb-3">Informasi Almarhum / Almarhumah</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-lg border border-slate-200">
                <div>
                    <span class="text-slate-500 block">Nama Lengkap:</span>
                    <span class="font-semibold text-slate-800 text-sm">{{ $meninggal->penduduk->nama_lengkap ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block">NIK:</span>
                    <span class="font-mono font-semibold text-slate-800">{{ $meninggal->penduduk->nik ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block">Desa:</span>
                    <span class="text-slate-800 font-medium">{{ $meninggal->penduduk->desa->nama_desa ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block">Jenis Kelamin:</span>
                    <span class="text-slate-800 font-medium">{{ $meninggal->penduduk->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</span>
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-xs uppercase font-bold text-slate-400 tracking-wider mb-3">Rincian Kematian</h2>
            <div class="space-y-3">
                <div class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500">Tanggal Meninggal:</span>
                    <span class="font-semibold text-slate-800">{{ $meninggal->tanggal_meninggal->format('d F Y') }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500">Tempat Meninggal:</span>
                    <span class="font-semibold text-slate-800">{{ $meninggal->tempat_meninggal }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500">Penyebab Kematian:</span>
                    <span class="font-semibold text-slate-800">{{ $meninggal->sebab_meninggal ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
