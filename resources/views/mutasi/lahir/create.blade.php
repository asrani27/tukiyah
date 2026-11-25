@extends('layouts.app')

@section('title', 'Catat Kelahiran')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Pencatatan Kelahiran Bayi</h1>
            <p class="text-xs text-slate-500 mt-1">Catat data kelahiran bayi baru di Kecamatan Bati-Bati.</p>
        </div>
        <a href="{{ route('mutasi.lahir.index') }}" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50">Kembali</a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <form action="{{ route('mutasi.lahir.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold uppercase mb-1">Kode Kelahiran *</label>
                    <input type="text" name="kode_kelahiran" value="{{ old('kode_kelahiran', $autoKode) }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 font-mono focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">Desa Domisili *</label>
                    <select name="desa_id" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                        <option value="">-- Pilih Desa --</option>
                        @foreach($desas as $desa)
                            <option value="{{ $desa->id }}" {{ old('desa_id') == $desa->id ? 'selected' : '' }}>{{ $desa->nama_desa }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block font-semibold uppercase mb-1">Ibu (Penduduk yang Melahirkan)</label>
                    <select name="nik_ibu" id="nik_ibu" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                        <option value="">-- Pilih Ibu --</option>
                        @foreach($ibu as $ibudata)
                            <option value="{{ $ibudata->nik }}" {{ old('nik_ibu') == $ibudata->nik ? 'selected' : '' }}>{{ $ibudata->nama_lengkap }} ({{ $ibudata->nik }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2 hidden" id="infoIbu">
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 space-y-2">
                        <h3 class="font-bold text-emerald-800 uppercase text-[11px] tracking-wide">Informasi Ibu</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-1.5 text-[11px] text-slate-700">
                            <div><span class="font-semibold">Nama:</span> <span id="infoNama">-</span></div>
                            <div><span class="font-semibold">Tempat Lahir:</span> <span id="infoTempatLahir">-</span></div>
                            <div><span class="font-semibold">Tanggal Lahir:</span> <span id="infoTanggalLahir">-</span></div>
                            <div><span class="font-semibold">Agama:</span> <span id="infoAgama">-</span></div>
                            <div><span class="font-semibold">Status:</span> <span id="infoStatus">-</span></div>
                            <div><span class="font-semibold">Pendidikan:</span> <span id="infoPendidikan">-</span></div>
                            <div class="sm:col-span-2"><span class="font-semibold">Alamat:</span> <span id="infoAlamat">-</span></div>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block font-semibold uppercase mb-1">Nama Bayi *</label>
                    <input type="text" name="nama_bayi" value="{{ old('nama_bayi') }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none" placeholder="Nama lengkap bayi">
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">Jenis Kelamin *</label>
                    <div class="flex items-center space-x-4 mt-2">
                        <label><input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin', 'L') == 'L' ? 'checked' : '' }} required> Laki-Laki</label>
                        <label><input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }} required> Perempuan</label>
                    </div>
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">Tempat Lahir *</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', 'Bati-Bati') }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">Tanggal Lahir *</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', date('Y-m-d')) }}" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                </div>
                <div>
                    <label class="block font-semibold uppercase mb-1">NIK Ayah (16 Digit)</label>
                    <input type="text" name="nik_ayah" maxlength="16" value="{{ old('nik_ayah') }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 font-mono focus:outline-none" placeholder="16 digit NIK ayah kandung">
                </div>
                <div class="md:col-span-2">
                    <label class="block font-semibold uppercase mb-1">Nama Ayah</label>
                    <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none" placeholder="Nama lengkap ayah kandung">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('mutasi.lahir.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 font-medium text-slate-700 hover:bg-slate-50">Batal</a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-semibold shadow-sm">Simpan Data Kelahiran</button>
            </div>
        </form>
    </div>
</div>

<script>
    const ibuData = @json($ibuData);
    const ibuSelect = document.getElementById('nik_ibu');
    const infoPanel = document.getElementById('infoIbu');

    function renderInfoIbu() {
        const data = ibuSelect ? ibuData[ibuSelect.value] : null;

        if (!data) {
            if (infoPanel) {
                infoPanel.classList.add('hidden');
            }
            return;
        }

        if (infoPanel) {
            infoPanel.classList.remove('hidden');
        }

        const setText = (id, value) => {
            const el = document.getElementById(id);
            if (el) {
                el.textContent = value || '-';
            }
        };

        setText('infoNama', data.nama_lengkap);
        setText('infoTempatLahir', data.tempat_lahir);
        setText('infoTanggalLahir', data.tanggal_lahir);
        setText('infoAgama', data.agama);
        setText('infoStatus', data.status_perkawinan);
        setText('infoPendidikan', data.pendidikan_terakhir);
        setText('infoAlamat', data.alamat);
    }

    if (ibuSelect) {
        ibuSelect.addEventListener('change', renderInfoIbu);
        renderInfoIbu();
    }
</script>
@endsection
