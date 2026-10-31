@extends('layouts.app')

@section('title', 'Kelola User Admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Kelola Pengguna Admin</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar petugas/operator yang berhak mengakses sistem informasi kependudukan.</p>
        </div>
        <div>
            <a href="{{ route('users.create') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Tambah Pengguna</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase font-semibold">
                    <tr>
                        <th class="px-4 py-3 w-12 text-center">No</th>
                        <th class="px-4 py-3">Nama Lengkap</th>
                        <th class="px-4 py-3">Username</th>
                        <th class="px-4 py-3">Terdaftar Sejak</th>
                        <th class="px-4 py-3 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $index => $u)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-4 py-3 text-center text-slate-400">{{ $users->firstItem() + $index }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $u->nama }}</td>
                            <td class="px-4 py-3 text-slate-600 font-mono text-[11px]">{{ $u->username }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $u->created_at ? $u->created_at->format('d/m/Y H:i') : '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="inline-flex items-center space-x-2">
                                    <a href="{{ route('users.edit', $u) }}" class="px-2.5 py-1 rounded bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 text-[11px] font-semibold">Edit</a>
                                    @if(auth()->id() !== $u->id)
                                        <form action="{{ route('users.destroy', $u) }}" method="POST" onsubmit="return confirm('Hapus user ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 rounded bg-red-50 text-red-700 border border-red-200 hover:bg-red-100 text-[11px] font-semibold">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-400">Belum ada data user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
