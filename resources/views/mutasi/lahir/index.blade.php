@extends('layouts.app')

@section('title', 'Pencatatan Kelahiran')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Pencatatan Kelahiran</h1>
            <p class="text-xs text-slate-500 mt-1">Data kelahiran bayi warga di wilayah Kecamatan Bati-Bati.</p>
        </div>
        <a href="{{ route('mutasi.lahir.create') }}" class="inline-flex items-center space-x-1 px-4 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm">
            <span>+ Catat Kelahiran Baru</span>
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('mutasi.lahir.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3 text-xs">
            <div class="md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode, nama bayi, NIK ibu/ayah..." class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
            </div>
            <div>
                <select name="bulan" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                    <option value="">-- Semua Bulan --</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <select name="tahun" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
                    <option value="">-- Semua Tahun --</option>
                    @foreach(range(date('Y'), date('Y') - 5) as $y)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
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
                        <th class="px-4 py-3">Kode / Bayi</th>
                        <th class="px-4 py-3">JK</th>
                        <th class="px-4 py-3">Kelahiran</th>
                        <th class="px-4 py-3">Desa</th>
                        <th class="px-4 py-3">NIK Ortu</th>
                        <th class="px-4 py-3 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kelahiran as $index => $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-4 py-3 text-center text-slate-400">{{ $kelahiran->firstItem() + $index }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-800">
                                <div>{{ $item->nama_bayi }}</div>
                                <div class="text-[11px] font-mono text-slate-400 font-normal">{{ $item->kode_kelahiran }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @if($item->jenis_kelamin == 'L')
                                    <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-bold text-[10px]">L</span>
                                @else
                                    <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-700 font-bold text-[10px]">P</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                <div>{{ $item->tempat_lahir }}</div>
                                <div class="text-[11px] text-slate-400">{{ $item->tanggal_lahir->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $item->desa->nama_desa ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-500 font-mono text-[11px]">
                                <div>Ibu: {{ $item->nik_ibu ?? '-' }}</div>
                                <div>Ayah: {{ $item->nik_ayah ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="inline-flex items-center space-x-1.5">
                                    <a href="{{ route('mutasi.lahir.edit', $item) }}" class="px-2.5 py-1 rounded bg-amber-50 text-amber-700 hover:bg-amber-100 text-[11px] font-semibold">Edit</a>
                                    <form action="{{ route('mutasi.lahir.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus data kelahiran ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded bg-red-50 text-red-700 hover:bg-red-100 text-[11px] font-semibold">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-400">Belum ada catatan kelahiran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($kelahiran->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
                {{ $kelahiran->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
