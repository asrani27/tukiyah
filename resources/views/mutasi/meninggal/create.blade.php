@extends('layouts.app')

@section('title', 'Catat Penduduk Meninggal')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Pencatatan Kematian Warga</h1>
            <p class="text-xs text-slate-500 mt-1">Status kependudukan warga akan otomatis berubah menjadi "Meninggal".</p>
        </div>
        <a href="{{ route('mutasi.meninggal.index') }}" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50">Kembali</a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <form action="{{ route('mutasi.meninggal.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold uppercase mb-1">Kode Kematian *</label>
                    <input type="text" name="kode_kematian" value="{{ old('kode_kematian', $autoKode) }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 font-mono focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">Tanggal Meninggal *</label>
                    <input type="date" name="tanggal_meninggal" value="{{ old('tanggal_meninggal', date('Y-m-d')) }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                </div>
                <div class="md:col-span-2">
                    <label class="block font-semibold uppercase mb-1">Pilih Penduduk (Status Tetap) *</label>
                    <select name="penduduk_id" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                        <option value="">-- Pilih Penduduk --</option>
                        @foreach($pendudukTetap as $p)
                            <option value="{{ $p->id }}" {{ old('penduduk_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nik }} - {{ $p->nama_lengkap }} (Desa {{ $p->desa->nama_desa ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">Tempat Meninggal *</label>
                    <input type="text" name="tempat_meninggal" value="{{ old('tempat_meninggal', 'Rumah Duka') }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none" placeholder="Contoh: Rumah Duka / RSUD">
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">Penyebab Kematian</label>
                    <input type="text" name="sebab_meninggal" value="{{ old('sebab_meninggal', 'Sakit Tua / Usia Lanjut') }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none" placeholder="Contoh: Sakit Biasa, Usia Lanjut, dll">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('mutasi.meninggal.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 font-medium text-slate-700 hover:bg-slate-50">Batal</a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-semibold shadow-sm">Simpan Data Kematian</button>
            </div>
        </form>
    </div>
</div>
@endsection
