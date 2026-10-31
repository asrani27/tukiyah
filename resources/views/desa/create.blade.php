@extends('layouts.app')

@section('title', 'Tambah Desa')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Tambah Data Desa</h1>
            <p class="text-xs text-slate-500 mt-1">Tambahkan data desa baru di wilayah Kecamatan Bati-Bati.</p>
        </div>
        <a href="{{ route('desa.index') }}" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50">Kembali</a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <form action="{{ route('desa.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="kode_desa" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Kode Desa *</label>
                <input type="text" name="kode_desa" id="kode_desa" value="{{ old('kode_desa') }}" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-600 focus:outline-none" placeholder="Contoh: 63.01.05.2013">
            </div>

            <div>
                <label for="nama_desa" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nama Desa *</label>
                <input type="text" name="nama_desa" id="nama_desa" value="{{ old('nama_desa') }}" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-600 focus:outline-none" placeholder="Contoh: Desa Bati-Bati">
            </div>

            <div>
                <label for="kepala_desa" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Kepala Desa / Pj.</label>
                <input type="text" name="kepala_desa" id="kepala_desa" value="{{ old('kepala_desa') }}" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-600 focus:outline-none" placeholder="Nama Kepala Desa">
            </div>

            <div>
                <label for="keterangan" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-600 focus:outline-none" placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('desa.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-xs font-medium text-slate-700 hover:bg-slate-50">Batal</a>
                <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm">Simpan Desa</button>
            </div>
        </form>
    </div>
</div>
@endsection
