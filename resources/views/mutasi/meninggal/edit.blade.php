@extends('layouts.app')

@section('title', 'Edit Data Kematian')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Edit Data Kematian</h1>
            <p class="text-xs text-slate-500 mt-1">Almarhum/ah: <strong class="text-slate-800">{{ $meninggal->penduduk->nama_lengkap ?? '-' }}</strong> (NIK: {{ $meninggal->penduduk->nik ?? '-' }})</p>
        </div>
        <a href="{{ route('mutasi.meninggal.index') }}" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50">Kembali</a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <form action="{{ route('mutasi.meninggal.update', $meninggal) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-semibold text-slate-700 uppercase mb-1">Tanggal Meninggal *</label>
                    <input type="date" name="tanggal_meninggal" value="{{ old('tanggal_meninggal', $meninggal->tanggal_meninggal ? $meninggal->tanggal_meninggal->format('Y-m-d') : '') }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 uppercase mb-1">Waktu Meninggal</label>
                    <input type="time" name="waktu_meninggal" value="{{ old('waktu_meninggal', $meninggal->waktu_meninggal ? \Illuminate\Support\Carbon::parse($meninggal->waktu_meninggal)->format('H:i') : '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 uppercase mb-1">Tempat Meninggal *</label>
                    <input type="text" name="tempat_meninggal" value="{{ old('tempat_meninggal', $meninggal->tempat_meninggal) }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 uppercase mb-1">Penyebab Kematian *</label>
                    <input type="text" name="penyebab_kematian" value="{{ old('penyebab_kematian', $meninggal->penyebab_kematian) }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 uppercase mb-1">Nama Pelapor *</label>
                    <input type="text" name="pelapor" value="{{ old('pelapor', $meninggal->pelapor) }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 uppercase mb-1">Hubungan Pelapor *</label>
                    <select name="hubungan_pelapor" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                        @foreach(['Suami', 'Istri', 'Anak', 'Orang Tua', 'Saudara Kandung', 'Ketua RT', 'Lainnya'] as $hub)
                            <option value="{{ $hub }}" {{ old('hubungan_pelapor', $meninggal->hubungan_pelapor) == $hub ? 'selected' : '' }}>{{ $hub }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block font-semibold text-slate-700 uppercase mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="2" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">{{ old('keterangan', $meninggal->keterangan) }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('mutasi.meninggal.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-xs font-medium text-slate-700 hover:bg-slate-50">Batal</a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
