@extends('layouts.app')

@section('title', 'Catat Pendatang')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Catat Pendatang Baru</h1>
            <p class="text-xs text-slate-500 mt-1">Pencatatan data warga pendatang yang berdomisili di Kecamatan Bati-Bati.</p>
        </div>
        <a href="{{ route('mutasi.pendatang.index') }}" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50">Kembali</a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <form action="{{ route('mutasi.pendatang.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold uppercase mb-1">NIK (16 Digit) *</label>
                    <input type="text" name="nik" maxlength="16" value="{{ old('nik') }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 font-mono focus:outline-none" placeholder="16 digit NIK">
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">Nama Lengkap *</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">Tempat Lahir *</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">Tanggal Lahir *</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">Jenis Kelamin *</label>
                    <div class="flex items-center space-x-4 mt-2">
                        <label><input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin', 'L') == 'L' ? 'checked' : '' }} required> Laki-Laki</label>
                        <label><input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }} required> Perempuan</label>
                    </div>
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">Agama *</label>
                    <select name="agama" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                        @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agm)
                            <option value="{{ $agm }}" {{ old('agama', 'Islam') == $agm ? 'selected' : '' }}>{{ $agm }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">Status Perkawinan *</label>
                    <select name="status_perkawinan" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                        @foreach(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'] as $sp)
                            <option value="{{ $sp }}" {{ old('status_perkawinan') == $sp ? 'selected' : '' }}>{{ $sp }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">Pendidikan Terakhir</label>
                    <select name="pendidikan_terakhir" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                        @foreach(['Tidak / Belum Sekolah', 'SD / Sederajat', 'SLTP / Sederajat', 'SLTA / Sederajat', 'Diploma I / II / III', 'Diploma IV / S1', 'S2', 'S3'] as $pend)
                            <option value="{{ $pend }}" {{ old('pendidikan_terakhir', 'SLTA / Sederajat') == $pend ? 'selected' : '' }}>{{ $pend }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">Desa Tujuan di Bati-Bati *</label>
                    <select name="desa_id" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                        <option value="">-- Pilih Desa Tujuan --</option>
                        @foreach($desas as $desa)
                            <option value="{{ $desa->id }}" {{ old('desa_id') == $desa->id ? 'selected' : '' }}>{{ $desa->nama_desa }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">Tanggal Datang *</label>
                    <input type="date" name="tanggal_datang" value="{{ old('tanggal_datang', date('Y-m-d')) }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="telp" value="{{ old('telp') }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none" placeholder="08xxxxxxxxxx">
                </div>
                <div class="md:col-span-2">
                    <label class="block font-semibold uppercase mb-1">Alamat Asal Lengkap *</label>
                    <textarea name="alamat_asal" rows="2" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none" placeholder="Alamat asal lengkap (RT/RW, Kelurahan, Kecamatan, Kabupaten, Provinsi)">{{ old('alamat_asal') }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('mutasi.pendatang.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 font-medium text-slate-700 hover:bg-slate-50">Batal</a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-semibold shadow-sm">Simpan Data Pendatang</button>
            </div>
        </form>
    </div>
</div>
@endsection
