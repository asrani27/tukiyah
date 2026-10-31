@extends('layouts.app')

@section('title', 'Laporan Kependudukan & Mutasi')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Laporan Kependudukan & Cetak PDF</h1>
            <p class="text-xs text-slate-500 mt-1">Pilih filter dan format untuk pratinjau maupun cetak dokumen resmi PDF.</p>
        </div>
        <a href="{{ route('laporan.cetak-pdf', request()->query()) }}" target="_blank" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            <span>Cetak PDF Landscape A4</span>
        </a>
    </div>

    <!-- Filter Form -->
    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('laporan.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 text-xs">
            <div>
                <label class="block font-semibold text-slate-700 uppercase mb-1">Jenis Laporan</label>
                <select name="jenis" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                    <option value="penduduk_tetap" {{ $jenis == 'penduduk_tetap' ? 'selected' : '' }}>Data Penduduk Tetap</option>
                    <option value="penduduk_pindah" {{ $jenis == 'penduduk_pindah' ? 'selected' : '' }}>Mutasi Penduduk Pindah</option>
                    <option value="penduduk_meninggal" {{ $jenis == 'penduduk_meninggal' ? 'selected' : '' }}>Mutasi Penduduk Meninggal</option>
                    <option value="penduduk_lahir" {{ $jenis == 'penduduk_lahir' ? 'selected' : '' }}>Pencatatan Kelahiran</option>
                    <option value="penduduk_pendatang" {{ $jenis == 'penduduk_pendatang' ? 'selected' : '' }}>Penduduk Pendatang</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold text-slate-700 uppercase mb-1">Wilayah Desa</label>
                <select name="desa_id" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                    <option value="all" {{ $desaId == 'all' ? 'selected' : '' }}>-- Seluruh Desa --</option>
                    @foreach($desas as $desa)
                        <option value="{{ $desa->id }}" {{ $desaId == $desa->id ? 'selected' : '' }}>{{ $desa->nama_desa }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold text-slate-700 uppercase mb-1">Bulan</label>
                <select name="bulan" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                    <option value="all" {{ $bulan == 'all' ? 'selected' : '' }}>-- Semua Bulan --</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $bulan == (string)$m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold text-slate-700 uppercase mb-1">Tahun</label>
                <div class="flex gap-2">
                    <select name="tahun" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                        <option value="all" {{ $tahun == 'all' ? 'selected' : '' }}>-- Semua Tahun --</option>
                        @foreach(range(date('Y'), date('Y') - 5) as $y)
                            <option value="{{ $y }}" {{ $tahun == (string)$y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-slate-800 text-white font-semibold hover:bg-slate-700 whitespace-nowrap">Filter</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Preview Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-900">
                    @if($jenis == 'penduduk_tetap') Data Penduduk Tetap
                    @elseif($jenis == 'penduduk_pindah') Mutasi Penduduk Pindah
                    @elseif($jenis == 'penduduk_meninggal') Mutasi Penduduk Meninggal
                    @elseif($jenis == 'penduduk_lahir') Pencatatan Kelahiran
                    @elseif($jenis == 'penduduk_pendatang') Penduduk Pendatang
                    @endif
                </h2>
                <p class="text-[11px] text-slate-500 mt-0.5">Total: <strong class="text-slate-800">{{ $data->count() }}</strong> data tercatat.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            @if($jenis == 'penduduk_tetap')
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase font-semibold">
                        <tr>
                            <th class="px-4 py-3 w-12 text-center">No</th>
                            <th class="px-4 py-3">NIK & Nama Lengkap</th>
                            <th class="px-4 py-3">JK</th>
                            <th class="px-4 py-3">Tempat, Tgl Lahir</th>
                            <th class="px-4 py-3">Pekerjaan</th>
                            <th class="px-4 py-3">Desa</th>
                            <th class="px-4 py-3">Alamat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($data as $index => $row)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-3 text-center text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-semibold text-slate-800">
                                    <div>{{ $row->nama_lengkap }}</div>
                                    <div class="text-[11px] font-mono text-slate-400 font-normal">NIK: {{ $row->nik }}</div>
                                </td>
                                <td class="px-4 py-3">{{ $row->jenis_kelamin }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $row->tempat_lahir }}, {{ $row->tanggal_lahir->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $row->pekerjaan ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-600 font-medium">{{ $row->desa->nama_desa ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-500 truncate max-w-xs">{{ $row->alamat }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-8 text-center text-slate-400">Tidak ada data ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif($jenis == 'penduduk_pindah')
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase font-semibold">
                        <tr>
                            <th class="px-4 py-3 w-12 text-center">No</th>
                            <th class="px-4 py-3">Kode / Penduduk</th>
                            <th class="px-4 py-3">Tgl Pindah</th>
                            <th class="px-4 py-3">Desa Asal</th>
                            <th class="px-4 py-3">Alamat Tujuan</th>
                            <th class="px-4 py-3">Alasan Pindah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($data as $index => $row)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-3 text-center text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-semibold text-slate-800">
                                    <div>{{ $row->penduduk->nama_lengkap ?? '-' }}</div>
                                    <div class="text-[11px] font-mono text-slate-400 font-normal">{{ $row->kode_pindah }} | NIK: {{ $row->penduduk->nik ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3 text-slate-600">{{ $row->tanggal_pindah->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $row->penduduk->desa->nama_desa ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $row->alamat_tujuan }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $row->alasan_pindah ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400">Tidak ada data ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif($jenis == 'penduduk_meninggal')
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase font-semibold">
                        <tr>
                            <th class="px-4 py-3 w-12 text-center">No</th>
                            <th class="px-4 py-3">Kode / Almarhum</th>
                            <th class="px-4 py-3">Desa</th>
                            <th class="px-4 py-3">Tgl Meninggal</th>
                            <th class="px-4 py-3">Tempat</th>
                            <th class="px-4 py-3">Penyebab Kematian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($data as $index => $row)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-3 text-center text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-semibold text-slate-800">
                                    <div>{{ $row->penduduk->nama_lengkap ?? '-' }}</div>
                                    <div class="text-[11px] font-mono text-slate-400 font-normal">{{ $row->kode_kematian }} | NIK: {{ $row->penduduk->nik ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3 text-slate-600">{{ $row->penduduk->desa->nama_desa ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $row->tanggal_meninggal->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $row->tempat_meninggal }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $row->sebab_meninggal ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400">Tidak ada data ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif($jenis == 'penduduk_lahir')
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase font-semibold">
                        <tr>
                            <th class="px-4 py-3 w-12 text-center">No</th>
                            <th class="px-4 py-3">Kode / Nama Bayi</th>
                            <th class="px-4 py-3">JK</th>
                            <th class="px-4 py-3">Tempat, Tanggal Lahir</th>
                            <th class="px-4 py-3">Desa Domisili</th>
                            <th class="px-4 py-3">NIK Orang Tua</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($data as $index => $row)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-3 text-center text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-semibold text-slate-800">
                                    <div>{{ $row->nama_bayi }}</div>
                                    <div class="text-[11px] font-mono text-slate-400 font-normal">{{ $row->kode_kelahiran }}</div>
                                </td>
                                <td class="px-4 py-3">{{ $row->jenis_kelamin }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $row->tempat_lahir }}, {{ $row->tanggal_lahir->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $row->desa->nama_desa ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-500 font-mono text-[11px]">
                                    <div>Ibu: {{ $row->nik_ibu ?? '-' }}</div>
                                    <div>Ayah: {{ $row->nik_ayah ?? '-' }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400">Tidak ada data ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif($jenis == 'penduduk_pendatang')
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase font-semibold">
                        <tr>
                            <th class="px-4 py-3 w-12 text-center">No</th>
                            <th class="px-4 py-3">NIK & Nama Lengkap</th>
                            <th class="px-4 py-3">JK</th>
                            <th class="px-4 py-3">Tanggal Datang</th>
                            <th class="px-4 py-3">Desa Tujuan</th>
                            <th class="px-4 py-3">Alamat Asal</th>
                            <th class="px-4 py-3">No. Telepon</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($data as $index => $row)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-3 text-center text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-semibold text-slate-800">
                                    <div>{{ $row->nama_lengkap }}</div>
                                    <div class="text-[11px] font-mono text-slate-400 font-normal">NIK: {{ $row->nik }}</div>
                                </td>
                                <td class="px-4 py-3">{{ $row->jenis_kelamin }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $row->tanggal_datang ? $row->tanggal_datang->format('d/m/Y') : '-' }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $row->desa->nama_desa ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-600 truncate max-w-xs">{{ $row->alamat_asal }}</td>
                                <td class="px-4 py-3 text-slate-500 font-mono">{{ $row->telp ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-8 text-center text-slate-400">Tidak ada data ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
