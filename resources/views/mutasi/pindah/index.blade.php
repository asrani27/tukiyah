@extends('layouts.app')

@section('title', 'Mutasi Pindah Penduduk')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Mutasi Pindah Penduduk</h1>
            <p class="text-xs text-slate-500 mt-1">Pencatatan perpindahan penduduk keluar wilayah / antar wilayah.</p>
        </div>
        <a href="{{ route('mutasi.pindah.create') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm">
            <span>+ Catat Penduduk Pindah</span>
        </a>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('mutasi.pindah.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3 text-xs">
            <div class="md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode mutasi, NIK, nama..." class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none">
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
                        <th class="px-4 py-3">Kode / Penduduk</th>
                        <th class="px-4 py-3">Desa Asal</th>
                        <th class="px-4 py-3">Tgl Pindah</th>
                        <th class="px-4 py-3">Alamat Tujuan</th>
                        <th class="px-4 py-3">Alasan</th>
                        <th class="px-4 py-3 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pindahList as $index => $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-4 py-3 text-center text-slate-400">{{ $pindahList->firstItem() + $index }}</td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-slate-800">{{ $item->penduduk->nama_lengkap ?? '-' }}</div>
                                <div class="text-[11px] font-mono text-slate-400">{{ $item->kode_pindah }} | NIK: {{ $item->penduduk->nik ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $item->penduduk->desa->nama_desa ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600 font-medium">{{ $item->tanggal_pindah->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-slate-600 max-w-xs truncate">{{ $item->alamat_tujuan }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $item->alasan_pindah ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="inline-flex items-center space-x-1">
                                    <a href="{{ route('mutasi.pindah.show', $item) }}" class="px-2 py-1 rounded bg-blue-50 text-blue-700 hover:bg-blue-100 text-[11px] font-semibold">Detail</a>
                                    <form action="{{ route('mutasi.pindah.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus mutasi pindah ini? Status penduduk akan dikembalikan ke Tetap.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2 py-1 rounded bg-red-50 text-red-700 hover:bg-red-100 text-[11px] font-semibold">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-400">Belum ada mutasi pindah tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pindahList->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
                {{ $pindahList->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
