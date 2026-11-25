@extends('layouts.app')

@section('title', 'Master Data Desa')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Data Desa / Kelurahan</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar wilayah administratif desa di wilayah Kecamatan Bati-Bati, Kabupaten Tanah Laut.</p>
        </div>
        <div>
            <a href="{{ route('desa.create') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Tambah Desa</span>
            </a>
        </div>
    </div>

    <!-- Search bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('desa.index') }}" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau kode desa..." class="flex-1 px-3.5 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
            <button type="submit" class="px-4 py-2 rounded-lg bg-slate-800 text-white text-xs font-semibold hover:bg-slate-700">Cari</button>
            @if(request('search'))
                <a href="{{ route('desa.index') }}" class="px-3 py-2 rounded-lg border border-slate-300 text-xs text-slate-600 hover:bg-slate-50">Reset</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase font-semibold">
                    <tr>
                        <th class="px-4 py-3 w-12 text-center">No</th>
                        <th class="px-4 py-3">Kode Desa</th>
                        <th class="px-4 py-3">Nama Desa</th>
                        <th class="px-4 py-3">Kepala Desa</th>
                        <th class="px-4 py-3 text-center">Jml Penduduk</th>
                        <th class="px-4 py-3 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($desas as $index => $desa)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-4 py-3 text-center text-slate-400">{{ $desas->firstItem() + $index }}</td>
                            <td class="px-4 py-3 font-mono text-[11px] font-semibold text-slate-700">{{ $desa->kode_desa }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $desa->nama_desa }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $desa->kepala_desa ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold border border-emerald-200">
                                    {{ $desa->penduduk_tetap_count }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="inline-flex items-center space-x-2">
                                    <a href="{{ route('desa.edit', $desa) }}" class="px-2.5 py-1 rounded bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 text-[11px] font-semibold">Edit</a>
                                    <form action="{{ route('desa.destroy', $desa) }}" method="POST" onsubmit="return confirm('Hapus desa ini? Data penduduk di desa ini akan terdampak.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded bg-red-50 text-red-700 border border-red-200 hover:bg-red-100 text-[11px] font-semibold">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-400">Belum ada data desa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($desas->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
                {{ $desas->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
