@extends('layouts.app')

@section('title', 'Catat Penduduk Pindah')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Pencatatan Penduduk Pindah</h1>
            <p class="text-xs text-slate-500 mt-1">Status kependudukan warga akan otomatis berubah menjadi "Pindah".</p>
        </div>
        <a href="{{ route('mutasi.pindah.index') }}" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50">Kembali</a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <form action="{{ route('mutasi.pindah.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold uppercase mb-1">Kode Mutasi Pindah *</label>
                    <input type="text" name="kode_pindah" value="{{ old('kode_pindah', $autoKode) }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 font-mono focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">Tanggal Pindah *</label>
                    <input type="date" name="tanggal_pindah" value="{{ old('tanggal_pindah', date('Y-m-d')) }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
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
                <div class="md:col-span-2">
                    <label class="block font-semibold uppercase mb-1">Alamat Tujuan Lengkap *</label>
                    <textarea name="alamat_tujuan" rows="2" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none" placeholder="Tuliskan alamat tujuan (RT/RW, Desa, Kecamatan, Kab/Kota, Provinsi)...">{{ old('alamat_tujuan') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block font-semibold uppercase mb-1">Alasan Pindah</label>
                    <input type="text" name="alasan_pindah" value="{{ old('alasan_pindah') }}" placeholder="Contoh: Pekerjaan, Menikah, Mengikuti Suami / Orang Tua..." class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('mutasi.pindah.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 font-medium text-slate-700 hover:bg-slate-50">Batal</a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-semibold shadow-sm">Simpan Mutasi Pindah</button>
            </div>
        </form>
    </div>
</div>
@endsection
