<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Petugas - SIPEKAN Kecamatan Bati-Bati</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-slate-800 antialiased font-sans min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md mx-auto">
        <!-- Logo / Header -->
        <div class="w-full flex flex-col items-center justify-center text-center mb-6">
            <div class="flex items-center justify-center mb-3">
                <img src="{{ asset('logo/tala.png') }}" alt="Logo Pemerintah Kabupaten Tanah Laut" class="w-20 h-auto max-h-24 object-contain mx-auto drop-shadow-xl">
            </div>
            <div class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-emerald-950/80 border border-emerald-700/60 text-emerald-300 text-xs font-semibold uppercase tracking-wider mb-2">
                Pemerintah Kabupaten Tanah Laut
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">SIPEKAN BATI-BATI</h1>
            <p class="text-sm text-slate-400 mt-1">Sistem Informasi Kependudukan Kantor Kecamatan Bati-Bati</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 border border-slate-100">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-slate-900">Masuk ke Sistem</h2>
                <p class="text-xs text-slate-500 mt-1">Silakan masukkan username dan password akun admin Anda.</p>
            </div>

            @if(session('info'))
                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 text-blue-800 text-xs rounded-lg">
                    {{ session('info') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded-lg">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="username" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
                        class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-transparent text-sm placeholder-slate-400"
                        placeholder="Masukkan username admin">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-transparent text-sm placeholder-slate-400"
                        placeholder="Masukkan password">
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300">
                        <span class="ml-2">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-sm shadow-md transition duration-150 flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Masuk ke Sistem</span>
                </button>
            </form>

            <div class="mt-6 pt-4 border-t border-slate-100 bg-slate-50 -mx-6 -mb-6 p-4 rounded-b-2xl text-center">
                <span class="text-xs text-slate-500">Akun Default: <strong class="text-slate-700">admin</strong> / <strong class="text-slate-700">password123</strong></span>
            </div>
        </div>

        <div class="text-center mt-6 text-xs text-slate-400">
            &copy; {{ date('Y') }} Kantor Kecamatan Bati-Bati, Kabupaten Tanah Laut
        </div>
    </div>
</body>
</html>
