@extends('layouts.app')

@section('title', 'Edit Pengguna Admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Edit Pengguna Admin</h1>
            <p class="text-xs text-slate-500 mt-1">Ubah nama atau password petugas.</p>
        </div>
        <a href="{{ route('users.index') }}" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50">Kembali</a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="nama" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap *</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $user->nama) }}" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-600 focus:outline-none">
            </div>

            <div>
                <label for="username" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Username *</label>
                <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-600 focus:outline-none">
            </div>

            <div class="p-3 bg-amber-50 rounded-lg border border-amber-200 text-xs text-amber-800">
                Kosongkan form password jika tidak ingin mengganti password.
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Password Baru (Opsional)</label>
                <input type="password" name="password" id="password" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-600 focus:outline-none" placeholder="Isi jika ingin mengubah password">
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-600 focus:outline-none" placeholder="Ulangi password baru">
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('users.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-xs font-medium text-slate-700 hover:bg-slate-50">Batal</a>
                <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
