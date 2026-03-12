<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APTAA System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen text-slate-900">

<div x-data="{ sidebarOpen: false }" class="min-h-screen relative flex flex-col">
    
    <div x-show="sidebarOpen" class="fixed inset-0 z-50 flex" x-cloak>
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <aside x-show="sidebarOpen" class="relative flex-1 flex flex-col max-w-xs w-full bg-white shadow-2xl">
            <div class="h-16 flex items-center justify-between px-6 bg-blue-600 text-white">
                <span class="font-bold tracking-wider">APTAA MENU</span>
                <button @click="sidebarOpen = false" class="text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <nav class="flex-1 p-6 space-y-4">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-2xl {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 font-semibold' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    <span>Dashboard</span>
                </a>
                
                @if(auth()->user()->role === 'tim_gudang')
                <a href="{{ route('stok_masuk.create') }}" class="flex items-center space-x-3 p-3 rounded-2xl {{ request()->routeIs('stok_masuk.create') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 font-semibold' }} transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    <span>Input Stok Masuk</span>
                </a>

                <a href="{{ route('stok_masuk.index') }}" class="flex items-center space-x-3 p-3 rounded-2xl {{ request()->routeIs('stok_masuk.index') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 font-semibold' }} transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    <span>Riwayat Stok Datang</span>
                </a>
                @endif
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('penjualan.create') }}" class="flex items-center space-x-3 p-3 rounded-2xl {{ request()->routeIs('penjualan.create') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 font-semibold' }} transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Input Penjualan</span>
                    </a>
                    <a href="{{ route('penjualan.index') }}" class="flex items-center space-x-3 p-3 rounded-2xl {{ request()->routeIs('penjualan.index') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-slate-50 font-semibold' }} transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v7.5m2.25-6.466a9.016 9.016 0 0 0-3.461-.203c-.536.072-.974.478-1.021 1.017a4.559 4.559 0 0 0-.018.402c0 .464.336.844.775.994l2.95 1.012c.44.15.775.53.775.994 0 .136-.006.27-.018.402-.047.539-.485.945-1.021 1.017a9.077 9.077 0 0 1-3.461-.203M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        <span>Riwayat Penjualan</span>
                    </a>
                    <a href="{{ route('monitor-stok.index') }}" class="flex items-center space-x-3 p-3 rounded-2xl {{ request()->routeIs('monitor-stok.index') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-blue-50 hover:text-blue-600 font-semibold' }} transition-all group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span>Monitor Stok</span>
                    </a>
                @endif
                @if(auth()->user()->role === 'tim_barang')
                    <a href="{{ route('cek-stok.create') }}" class="flex items-center space-x-3 p-3 rounded-2xl {{ request()->routeIs('cek-stok.create') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-blue-50 hover:text-blue-600 font-semibold' }} transition-all group">
                        <span>Input Data Stok</span>
                    </a>
                    <a href="{{ route('cek-stok.index') }}" class="flex items-center space-x-3 p-3 rounded-2xl {{ request()->routeIs('cek-stok.index') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-500 hover:bg-blue-50 hover:text-blue-600 font-semibold' }} transition-all group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <span>Riwayat Cek Fisik</span>
                    </a>
                @endif
                </nav>
            <div class="p-6 border-t border-slate-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center p-3 rounded-2xl bg-red-50 text-red-600 font-bold hover:bg-red-100 transition-all text-sm">Logout System</button>
                </form>
            </div>
        </aside>
    </div>

    <header class="h-20 bg-white/70 backdrop-blur-lg border-b border-blue-100 flex items-center justify-between px-6 sticky top-0 z-40 shadow-sm">
        <div class="flex items-center space-x-4">
            <button @click="sidebarOpen = true" class="p-2 rounded-xl bg-blue-600 text-white shadow-md hover:bg-blue-700 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
            <h2 class="font-extrabold text-blue-600 tracking-tight text-xl">APTAA <span class="text-slate-800">SYSTEM</span></h2>
        </div>
        <div class="hidden sm:flex items-center space-x-3 bg-blue-50/50 py-1.5 pl-1.5 pr-4 rounded-full border border-blue-100">
            <img src="https://i.pravatar.cc/100?u={{ auth()->user()->id }}" class="h-8 w-8 rounded-full border-2 border-white shadow-sm">
            <span class="text-sm font-bold text-slate-700 uppercase tracking-tight">{{ explode(' ', auth()->user()->name)[0] }}</span>
        </div>
    </header>

    <main class="flex-1 p-6 md:p-10 max-w-7xl mx-auto w-full">
        @yield('content')
    </main>

</div>
</body>
</html>