@extends('layouts.app')

@section('title', 'Data Penduduk')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Master Data Penduduk</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar seluruh warga kependudukan di wilayah Kecamatan Bati-Bati.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('penduduk.create') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm transition">
                <span>+ Tambah Penduduk</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('penduduk.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="NIK, Nama, KK..." class="w-full px-3 py-1.5 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Desa</label>
                <select name="desa_id" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                    <option value="">-- Semua Desa --</option>
                    @foreach($desas as $desa)
                        <option value="{{ $desa->id }}" {{ request('desa_id') == $desa->id ? 'selected' : '' }}>{{ $desa->nama_desa }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Status Keberadaan</label>
                <select name="status_keberadaan" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                    <option value="">-- Semua Status --</option>
                    <option value="tetap" {{ request('status_keberadaan') == 'tetap' ? 'selected' : '' }}>Tetap</option>
                    <option value="pindah" {{ request('status_keberadaan') == 'pindah' ? 'selected' : '' }}>Pindah</option>
                    <option value="meninggal" {{ request('status_keberadaan') == 'meninggal' ? 'selected' : '' }}>Meninggal</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 py-1.5 px-3 rounded-lg bg-slate-800 text-white text-xs font-semibold hover:bg-slate-700">Filter</button>
                @if(request()->hasAny(['search', 'desa_id', 'status_keberadaan']))
                    <a href="{{ route('penduduk.index') }}" class="py-1.5 px-3 rounded-lg border border-slate-300 text-xs text-slate-600 hover:bg-slate-50">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase font-semibold">
                    <tr>
                        <th class="px-4 py-3 w-12 text-center">No</th>
                        <th class="px-4 py-3">NIK / KK</th>
                        <th class="px-4 py-3">Nama Lengkap</th>
                        <th class="px-4 py-3">JK</th>
                        <th class="px-4 py-3">TTL</th>
                        <th class="px-4 py-3">Desa</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($penduduks as $index => $p)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-4 py-3 text-center text-slate-400">{{ $penduduks->firstItem() + $index }}</td>
                            <td class="px-4 py-3 font-mono">
                                <div class="font-semibold text-slate-800">{{ $p->nik }}</div>
                                <div class="text-[11px] text-slate-400">KK: {{ $p->no_kk }}</div>
                            </td>
                            <td class="px-4 py-3 font-semibold text-slate-800">
                                <div>{{ $p->nama_lengkap }}</div>
                                <div class="text-[11px] text-slate-400 font-normal">{{ $p->status_keluarga ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @if($p->jenis_kelamin == 'L')
                                    <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-bold text-[10px]">L</span>
                                @else
                                    <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-700 font-bold text-[10px]">P</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                <div>{{ $p->tempat_lahir }}</div>
                                <div class="text-[11px] text-slate-400">{{ $p->tanggal_lahir ? $p->tanggal_lahir->format('d/m/Y') : '-' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-800">{{ $p->desa->nama_desa ?? '-' }}</div>
                                <div class="text-[11px] text-slate-400 truncate max-w-xs">{{ $p->alamat }} RT {{ $p->rt }}/RW {{ $p->rw }}</div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($p->status_keberadaan == 'tetap')
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Tetap</span>
                                @elseif($p->status_keberadaan == 'pindah')
                                    <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">Pindah</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 text-[10px] font-bold">Meninggal</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="inline-flex items-center space-x-1.5">
                                    <a href="{{ route('penduduk.show', $p) }}" class="px-2 py-1 rounded bg-slate-100 text-slate-700 hover:bg-slate-200 text-[11px] font-medium">Detail</a>
                                    <a href="{{ route('penduduk.edit', $p) }}" class="px-2 py-1 rounded bg-amber-50 text-amber-700 hover:bg-amber-100 text-[11px] font-medium">Edit</a>
                                    <form action="{{ route('penduduk.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus penduduk ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2 py-1 rounded bg-red-50 text-red-700 hover:bg-red-100 text-[11px] font-medium">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-slate-400">Tidak ada data penduduk yang sesuai kriteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($penduduks->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
                {{ $penduduks->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
