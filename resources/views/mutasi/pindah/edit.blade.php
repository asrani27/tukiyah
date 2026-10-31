@extends('layouts.app')

@section('title', 'Edit Data Penduduk Pindah')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Edit Data Penduduk Pindah</h1>
            <p class="text-xs text-slate-500 mt-1">Penduduk: <strong class="text-slate-800">{{ $pindah->penduduk->nama_lengkap ?? '-' }}</strong> (NIK: {{ $pindah->penduduk->nik ?? '-' }})</p>
        </div>
        <a href="{{ route('mutasi.pindah.index') }}" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50">Kembali</a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <form action="{{ route('mutasi.pindah.update', $pindah) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-semibold text-slate-700 uppercase mb-1">Tanggal Pindah *</label>
                    <input type="date" name="tanggal_pindah" value="{{ old('tanggal_pindah', $pindah->tanggal_pindah ? $pindah->tanggal_pindah->format('Y-m-d') : '') }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 uppercase mb-1">Alasan Pindah</label>
                    <select name="alasan_pindah" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                        @foreach(['Pekerjaan', 'Pendidikan', 'Keamanan', 'Kesehatan', 'Perumahan', 'Keluarga', 'Lainnya'] as $alasan)
                            <option value="{{ $alasan }}" {{ old('alasan_pindah', $pindah->alasan_pindah) == $alasan ? 'selected' : '' }}>{{ $alasan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block font-semibold text-slate-700 uppercase mb-1">Alamat Tujuan Lengkap *</label>
                    <input type="text" name="alamat_tujuan" value="{{ old('alamat_tujuan', $pindah->alamat_tujuan) }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 uppercase mb-1">Desa / Kelurahan Tujuan *</label>
                    <input type="text" name="desa_tujuan" value="{{ old('desa_tujuan', $pindah->desa_tujuan) }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 uppercase mb-1">Kecamatan Tujuan *</label>
                    <input type="text" name="kecamatan_tujuan" value="{{ old('kecamatan_tujuan', $pindah->kecamatan_tujuan) }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 uppercase mb-1">Kabupaten / Kota Tujuan *</label>
                    <input type="text" name="kabupaten_tujuan" value="{{ old('kabupaten_tujuan', $pindah->kabupaten_tujuan) }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 uppercase mb-1">Provinsi Tujuan *</label>
                    <input type="text" name="provinsi_tujuan" value="{{ old('provinsi_tujuan', $pindah->provinsi_tujuan) }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                </div>
                <div class="md:col-span-2">
                    <label class="block font-semibold text-slate-700 uppercase mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="2" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">{{ old('keterangan', $pindah->keterangan) }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('mutasi.pindah.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-xs font-medium text-slate-700 hover:bg-slate-50">Batal</a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
