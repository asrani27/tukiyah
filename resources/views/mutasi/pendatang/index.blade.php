@extends('layouts.app')

@section('title', 'Data Penduduk Pendatang')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Data Penduduk Pendatang</h1>
            <p class="text-xs text-slate-500 mt-1">Pencatatan warga pendatang yang masuk ke wilayah Kecamatan Bati-Bati.</p>
        </div>
        <a href="{{ route('mutasi.pendatang.create') }}" class="inline-flex items-center space-x-1 px-4 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm">
            <span>+ Catat Pendatang Baru</span>
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('mutasi.pendatang.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3 text-xs">
            <div class="md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIK, nama lengkap, atau alamat asal..." class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
            </div>
            <div>
                <select name="desa_id" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                    <option value="">-- Semua Desa --</option>
                    @foreach($desas as $d)
                        <option value="{{ $d->id }}" {{ request('desa_id') == $d->id ? 'selected' : '' }}>{{ $d->nama_desa }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <select name="bulan" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                    <option value="">-- Bulan --</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 rounded-lg bg-slate-800 text-white font-semibold">Filter</button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase font-semibold">
                    <tr>
                        <th class="px-4 py-3 w-12 text-center">No</th>
                        <th class="px-4 py-3">NIK / Nama Pendatang</th>
                        <th class="px-4 py-3">JK</th>
                        <th class="px-4 py-3">Tanggal Datang</th>
                        <th class="px-4 py-3">Desa Tujuan</th>
                        <th class="px-4 py-3">Alamat Asal</th>
                        <th class="px-4 py-3 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pendatang as $index => $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-4 py-3 text-center text-slate-400">{{ $pendatang->firstItem() + $index }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-800">
                                <div>{{ $item->nama_lengkap }}</div>
                                <div class="text-[11px] font-mono text-slate-400 font-normal">NIK: {{ $item->nik }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @if($item->jenis_kelamin == 'L')
                                    <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-bold text-[10px]">L</span>
                                @else
                                    <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-700 font-bold text-[10px]">P</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-600 font-medium">{{ $item->tanggal_datang ? $item->tanggal_datang->format('d/m/Y') : '-' }}</td>
                            <td class="px-4 py-3 text-slate-600 font-medium">{{ $item->desa->nama_desa ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600 truncate max-w-xs">{{ $item->alamat_asal }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="inline-flex items-center space-x-1.5">
                                    <form action="{{ route('mutasi.pendatang.jadikan-tetap', $item) }}" method="POST" onsubmit="return confirm('Jadikan warga ini sebagai Penduduk Tetap?');" class="inline">
                                        @csrf
                                        <button type="submit" title="Jadikan Warga Tetap" class="px-2 py-1 rounded bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-[10px] font-bold">Tetap</button>
                                    </form>
                                    <a href="{{ route('mutasi.pendatang.edit', $item) }}" class="px-2 py-1 rounded bg-amber-50 text-amber-700 hover:bg-amber-100 text-[10px] font-semibold">Edit</a>
                                    <form action="{{ route('mutasi.pendatang.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus data pendatang ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2 py-1 rounded bg-red-50 text-red-700 hover:bg-red-100 text-[10px] font-semibold">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-400">Belum ada catatan pendatang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pendatang->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
                {{ $pendatang->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
