@extends('layouts.app')

@section('title', 'Tambah Data Penduduk')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Tambah Data Penduduk</h1>
            <p class="text-xs text-slate-500 mt-1">Formulir pendaftaran penduduk tetap baru di Kecamatan Bati-Bati.</p>
        </div>
        <a href="{{ route('penduduk.index') }}" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50">Kembali</a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <form action="{{ route('penduduk.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <h2 class="text-sm font-bold text-slate-800 pb-2 border-b border-slate-100 uppercase">1. Identitas Pribadi</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">NIK (16 Digit) *</label>
                        <input type="text" name="nik" maxlength="16" value="{{ old('nik') }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 font-mono text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none" placeholder="16 digit NIK">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Nomor KK (16 Digit) *</label>
                        <input type="text" name="no_kk" maxlength="16" value="{{ old('no_kk') }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 font-mono text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none" placeholder="16 digit KK">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Jenis Kelamin *</label>
                        <div class="flex items-center space-x-4 mt-2">
                            <label><input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} required class="text-emerald-600"> Laki-Laki</label>
                            <label><input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }} required class="text-emerald-600"> Perempuan</label>
                        </div>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Tempat Lahir *</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Tanggal Lahir *</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Agama *</label>
                        <select name="agama" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                            @foreach(['Islam', 'Kristen Protestan', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agm)
                                <option value="{{ $agm }}" {{ old('agama', 'Islam') == $agm ? 'selected' : '' }}>{{ $agm }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Golongan Darah</label>
                        <select name="golongan_darah" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                            <option value="-">-</option>
                            @foreach(['A', 'B', 'AB', 'O'] as $gd)
                                <option value="{{ $gd }}" {{ old('golongan_darah') == $gd ? 'selected' : '' }}>{{ $gd }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Status Perkawinan *</label>
                        <select name="status_perkawinan" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                            @foreach(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'] as $sp)
                                <option value="{{ $sp }}" {{ old('status_perkawinan') == $sp ? 'selected' : '' }}>{{ $sp }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Hubungan Keluarga *</label>
                        <select name="status_keluarga" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                            @foreach(['Kepala Keluarga', 'Istri', 'Anak', 'Famili Lain'] as $sk)
                                <option value="{{ $sk }}" {{ old('status_keluarga') == $sk ? 'selected' : '' }}>{{ $sk }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-sm font-bold text-slate-800 pb-2 border-b border-slate-100 uppercase">2. Pendidikan, Pekerjaan & Domisili</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Pendidikan Terakhir *</label>
                        <select name="pendidikan_terakhir" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                            @foreach(['Tidak / Belum Sekolah', 'SD / Sederajat', 'SLTP / Sederajat', 'SLTA / Sederajat', 'Diploma I / II / III', 'Diploma IV / S1', 'S2', 'S3'] as $pend)
                                <option value="{{ $pend }}" {{ old('pendidikan_terakhir', 'SLTA / Sederajat') == $pend ? 'selected' : '' }}>{{ $pend }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Pekerjaan *</label>
                        <input type="text" name="pekerjaan" value="{{ old('pekerjaan', 'Petani / Pekebun') }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Kewarganegaraan *</label>
                        <select name="kewarganegaraan" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                            <option value="WNI" {{ old('kewarganegaraan', 'WNI') == 'WNI' ? 'selected' : '' }}>WNI (Warga Negara Indonesia)</option>
                            <option value="WNA" {{ old('kewarganegaraan') == 'WNA' ? 'selected' : '' }}>WNA (Warga Negara Asing)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Desa / Kelurahan *</label>
                        <select name="desa_id" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                            <option value="">-- Pilih Desa --</option>
                            @foreach($desas as $desa)
                                <option value="{{ $desa->id }}" {{ old('desa_id') == $desa->id ? 'selected' : '' }}>{{ $desa->nama_desa }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Alamat Lengkap / Jalan *</label>
                        <input type="text" name="alamat" value="{{ old('alamat') }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none" placeholder="Contoh: Jl. Ahmad Yani Km. 32">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Telepon</label>
                        <input type="text" name="telp" value="{{ old('telp') }}" maxlength="20" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none" placeholder="08xxxxxxxxxx">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">RT *</label>
                        <input type="text" name="rt" value="{{ old('rt', '001') }}" required maxlength="4" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none" placeholder="001">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">RW *</label>
                        <input type="text" name="rw" value="{{ old('rw', '001') }}" required maxlength="4" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none" placeholder="001">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('penduduk.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-xs font-medium text-slate-700 hover:bg-slate-50">Batal</a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm">Simpan Penduduk</button>
            </div>
        </form>
    </div>
</div>
@endsection
