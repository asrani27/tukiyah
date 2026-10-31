@extends('layouts.app')

@section('title', 'Tambah Petugas Admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Tambah Pengguna Admin</h1>
            <p class="text-xs text-slate-500 mt-1">Daftarkan akun admin baru untuk operator sistem.</p>
        </div>
        <a href="{{ route('users.index') }}" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50">Kembali</a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="nama" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap *</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-600 focus:outline-none" placeholder="Contoh: Ahmad Rifani">
            </div>

            <div>
                <label for="username" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Username *</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-600 focus:outline-none" placeholder="Contoh: rifani_admin">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Password *</label>
                <input type="password" name="password" id="password" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-600 focus:outline-none" placeholder="Minimal 6 karakter">
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Konfirmasi Password *</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-600 focus:outline-none" placeholder="Ulangi password">
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('users.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-xs font-medium text-slate-700 hover:bg-slate-50">Batal</a>
                <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm">Simpan Akun</button>
            </div>
        </form>
    </div>
</div>
@endsection
