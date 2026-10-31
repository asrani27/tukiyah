<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SIPEKAN Kecamatan Bati-Bati</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-800 antialiased font-sans min-h-screen flex flex-col">
    <header class="bg-emerald-800 text-white shadow-md sticky top-0 z-40">
        <div class="px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            <div class="flex items-center space-x-3">
                <button id="sidebarToggle" type="button" class="lg:hidden p-2 rounded-lg hover:bg-emerald-700 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div>
                    <h1 class="text-base font-bold tracking-tight text-white leading-none">SIPEKAN BATI-BATI</h1>
                    <p class="text-xs text-emerald-200 mt-0.5 hidden sm:block">Sistem Informasi Kependudukan Kec. Bati-Bati, Kab. Tanah Laut</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="hidden md:flex flex-col text-right">
                    <span class="text-xs font-semibold text-white">{{ Auth::user()->nama ?? 'Administrator' }}</span>
                    <span class="text-[11px] text-emerald-300">Admin ({{ Auth::user()->username ?? 'admin' }})</span>
                </div>
                <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Keluar dari sistem?');">
                    @csrf
                    <button type="submit" class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-lg text-xs font-medium bg-red-600 hover:bg-red-700 text-white shadow-sm transition">
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </header>
    <div class="flex-1 flex overflow-hidden">
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-slate-900 text-slate-300 transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out flex flex-col pt-16 lg:static lg:pt-0">
            <div class="p-4 border-b border-slate-800 hidden lg:block bg-slate-950/40">
                <div class="text-[11px] uppercase tracking-wider text-slate-400 font-bold">Instansi Pemerintahan</div>
                <div class="text-sm font-semibold text-white truncate">Kantor Kec. Bati-Bati</div>
                <div class="text-xs text-slate-400">Kabupaten Tanah Laut</div>
            </div>
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto text-sm">
                <div class="px-3 pt-2 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">Menu Utama</div>
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('dashboard') || request()->routeIs('home') ? 'bg-emerald-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">Dashboard</a>

                <div class="px-3 pt-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">Master Data</div>
                <a href="{{ route('desa.index') }}" class="flex items-center px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('desa.*') ? 'bg-emerald-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">Data Desa / Kelurahan</a>
                <a href="{{ route('penduduk.index') }}" class="flex items-center px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('penduduk.*') ? 'bg-emerald-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">Data Penduduk Tetap</a>

                <div class="px-3 pt-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">Mutasi Kependudukan</div>
                <a href="{{ route('mutasi.pindah.index') }}" class="flex items-center px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('mutasi.pindah.*') ? 'bg-emerald-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">Penduduk Pindah</a>
                <a href="{{ route('mutasi.meninggal.index') }}" class="flex items-center px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('mutasi.meninggal.*') ? 'bg-emerald-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">Penduduk Meninggal</a>
                <a href="{{ route('mutasi.lahir.index') }}" class="flex items-center px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('mutasi.lahir.*') ? 'bg-emerald-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">Pencatatan Lahir</a>
                <a href="{{ route('mutasi.pendatang.index') }}" class="flex items-center px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('mutasi.pendatang.*') ? 'bg-emerald-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">Penduduk Pendatang</a>

                <div class="px-3 pt-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">Laporan & Pengaturan</div>
                <a href="{{ route('laporan.index') }}" class="flex items-center px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('laporan.*') ? 'bg-emerald-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">Cetak Laporan PDF</a>
                <a href="{{ route('users.index') }}" class="flex items-center px-3 py-2 rounded-lg font-medium transition {{ request()->routeIs('users.*') ? 'bg-emerald-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">Kelola User Admin</a>
            </nav>
            <div class="p-3 border-t border-slate-800 text-[11px] text-slate-400 text-center">&copy; {{ date('Y') }} Kec. Bati-Bati</div>
        </aside>
        <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 z-20 hidden lg:hidden"></div>

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            @if(session('success'))
                <div class="mb-5 p-4 bg-emerald-50 border-l-4 border-emerald-600 rounded-r-lg text-sm text-emerald-800 font-medium shadow-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-5 p-4 bg-red-50 border-l-4 border-red-600 rounded-r-lg text-sm text-red-800 font-medium shadow-sm">{{ session('error') }}</div>
            @endif
            @if(session('info'))
                <div class="mb-5 p-4 bg-blue-50 border-l-4 border-blue-600 rounded-r-lg text-sm text-blue-800 font-medium shadow-sm">{{ session('info') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-5 p-4 bg-amber-50 border-l-4 border-amber-600 rounded-r-lg shadow-sm">
                    <div class="text-sm font-semibold text-amber-800 mb-1">Periksa kembali input berikut:</div>
                    <ul class="list-disc list-inside text-xs text-amber-700 space-y-0.5">
                        @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (sidebarToggle && sidebar && overlay) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            });
            overlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }
    </script>
</body>
</html>
