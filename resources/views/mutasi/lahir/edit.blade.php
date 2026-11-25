@extends('layouts.app')

@section('title', 'Edit Data Kelahiran')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Edit Data Kelahiran</h1>
            <p class="text-xs text-slate-500 mt-1">Bayi: <strong class="text-slate-800">{{ $lahir->nama_bayi }}</strong></p>
        </div>
        <a href="{{ route('mutasi.lahir.index') }}" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50">Kembali</a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <form action="{{ route('mutasi.lahir.update', $lahir) }}" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold uppercase mb-1">Kode Kelahiran *</label>
                    <input type="text" name="kode_kelahiran" value="{{ old('kode_kelahiran', $lahir->kode_kelahiran) }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 font-mono focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">Desa Domisili *</label>
                    <select name="desa_id" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                        @foreach($desas as $desa)
                            <option value="{{ $desa->id }}" {{ old('desa_id', $lahir->desa_id) == $desa->id ? 'selected' : '' }}>{{ $desa->nama_desa }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block font-semibold uppercase mb-1">Nama Bayi *</label>
                    <input type="text" name="nama_bayi" value="{{ old('nama_bayi', $lahir->nama_bayi) }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">Jenis Kelamin *</label>
                    <div class="flex items-center space-x-4 mt-2">
                        <label><input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin', $lahir->jenis_kelamin) == 'L' ? 'checked' : '' }} required> Laki-Laki</label>
                        <label><input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin', $lahir->jenis_kelamin) == 'P' ? 'checked' : '' }} required> Perempuan</label>
                    </div>
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">Tempat Lahir *</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $lahir->tempat_lahir) }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">Tanggal Lahir *</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $lahir->tanggal_lahir ? $lahir->tanggal_lahir->format('Y-m-d') : '') }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">NIK Ibu (16 Digit)</label>
                    <input type="text" name="nik_ibu" maxlength="16" value="{{ old('nik_ibu', $lahir->nik_ibu) }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 font-mono focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">NIK Ayah (16 Digit)</label>
                    <input type="text" name="nik_ayah" maxlength="16" value="{{ old('nik_ayah', $lahir->nik_ayah) }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 font-mono focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">Nama Ayah</label>
                    <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $lahir->nama_ayah) }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none" placeholder="Nama lengkap ayah kandung">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('mutasi.lahir.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 font-medium text-slate-700 hover:bg-slate-50">Batal</a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-semibold shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
