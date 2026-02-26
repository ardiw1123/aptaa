<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | APTAA System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen text-slate-900">

<div x-data="{ sidebarOpen: false }" class="min-h-screen relative flex flex-col">
    
    <div 
        x-show="sidebarOpen" 
        class="fixed inset-0 z-50 flex" 
        x-cloak>
        
        <div 
            x-show="sidebarOpen"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        <aside 
            x-show="sidebarOpen"
            x-transition:enter="transition ease-in-out duration-300 transform"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="relative flex-1 flex flex-col max-w-xs w-full bg-white shadow-2xl">
            
            <div class="h-16 flex items-center justify-between px-6 bg-blue-600 text-white">
                <span class="font-bold tracking-wider">APTAA MENU</span>
                <button @click="sidebarOpen = false" class="text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 p-6 space-y-4">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-2xl bg-blue-50 text-blue-600 font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>
                @if(auth()->user()->role === 'admin')
                <a href="#" class="flex items-center space-x-3 p-3 rounded-2xl text-slate-500 hover:bg-slate-50 transition-all font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Kelola User</span>
                </a>
                @endif
                @if(auth()->user()->role === 'manajer')
                <a href="/stock/opname" class="text-slate-500 hover:bg-slate-50 p-3 block rounded-2xl">Approve Opname</a>
                <a href="/reports/inventory" class="text-slate-500 hover:bg-slate-50 p-3 block rounded-2xl">Laporan Bulanan</a>
                @endif
            </nav>

            <div class="p-6 border-t border-slate-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 p-3 rounded-2xl bg-red-50 text-red-600 font-bold hover:bg-red-100 transition-all text-sm">
                        <span>Logout System</span>
                    </button>
                </form>
            </div>
        </aside>
    </div>

    <header class="h-20 bg-white/70 backdrop-blur-lg border-b border-blue-100 flex items-center justify-between px-6 sticky top-0 z-40 shadow-sm">
        <div class="flex items-center space-x-4">
            <button @click="sidebarOpen = true" class="p-2 rounded-xl bg-blue-600 text-white shadow-md hover:bg-blue-700 focus:outline-none transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <h2 class="font-extrabold text-blue-600 tracking-tight text-xl">APTAA <span class="text-slate-800">SYSTEM</span></h2>
        </div>
        
        <div class="hidden sm:flex items-center space-x-3 bg-blue-50/50 py-1.5 pl-1.5 pr-4 rounded-full border border-blue-100">
            <img src="https://i.pravatar.cc/100?u={{ auth()->user()->id }}" class="h-8 w-8 rounded-full border-2 border-white shadow-sm">
            <span class="text-sm font-bold text-slate-700 uppercase tracking-tight">{{ explode(' ', auth()->user()->name)[0] }}</span>
        </div>
    </header>

    <main class="flex-1 p-6 md:p-10 max-w-7xl mx-auto w-full">
        
        <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6 mb-12 text-center md:text-left">
            <div class="relative">
                <img src="https://i.pravatar.cc/150?u={{ auth()->user()->id }}" class="h-24 w-24 md:h-28 md:w-28 rounded-[2rem] shadow-xl border-4 border-white object-cover">
                <div class="absolute -bottom-2 -right-2 h-6 w-6 bg-green-500 border-4 border-white rounded-full"></div>
            </div>
            <div class="pt-2">
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-800 tracking-tight">{{ auth()->user()->name }}</h1>
                <p class="text-blue-600 font-bold text-sm uppercase tracking-[0.2em] mt-1">{{ auth()->user()->role }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
            
            <div class="bg-white rounded-[1.2rem] shadow-xl shadow-blue-200/20 border border-white overflow-hidden flex flex-col">
                <div class="px-8 py-6 border-b border-blue-50 bg-blue-50/30">
                    <h4 class="font-bold text-slate-800 flex items-center">
                        <span class="h-2 w-2 bg-blue-600 rounded-full mr-3"></span>
                        Aktivitas Terakhir
                    </h4>
                </div>
                <div class="p-8 flex-1 flex items-center justify-between">
                    <div>
                        <p class="text-slate-700 font-medium">Input Data Penjualan</p>
                        <p class="text-xs text-slate-400 mt-1 uppercase tracking-widest font-bold">Gudang Utama</p>
                    </div>
                    <span class="text-xs font-bold px-3 py-1 bg-blue-50 text-blue-600 rounded-full">4j yang lalu</span>
                </div>
            </div>

            <div class="bg-white rounded-[1.2rem] shadow-xl shadow-blue-200/20 border border-white p-8 md:p-10 flex flex-col justify-center text-center relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-5">
                    <svg fill="currentColor" class="h-16 w-16 text-blue-600" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.899 14.899 16 16 16L19 16L19 14L16 14C14.343 14 13 12.657 13 11L13 7C13 5.343 14.343 4 16 4L19 4L19 7L16 7L16 10L19 10C20.657 10 22 11.343 22 13L22 19C22 20.657 20.657 22 19 22L16 22C14.899 22 14.017 21.101 14.017 21ZM4.017 21L4.017 18C4.017 16.899 4.899 16 6 16L9 16L9 14L6 14C4.343 14 3 12.657 3 11L3 7C3 5.343 4.343 4 6 4L9 4L9 7L6 7L6 10L9 10C10.657 10 12 11.343 12 13L12 19C12 20.657 10.657 22 9 22L6 22C4.899 22 4.017 21.101 4.017 21Z" /></svg>
                </div>
                <h4 class="text-[10px] font-extrabold text-blue-400 uppercase tracking-[0.3em] mb-4">Daily Insight</h4>
                <p class="text-slate-600 text-lg md:text-xl italic font-medium leading-relaxed">
                    "Everything that i spend my energy at, is dedicated to make me to be a better person"
                </p>
            </div>

        </div>

        <div class="mt-12 flex justify-center">
            <button class="group bg-blue-600 hover:bg-slate-900 text-white font-bold px-10 py-5 rounded-[2rem] shadow-2xl shadow-blue-600/30 transform hover:-translate-y-1 transition-all duration-300 flex items-center space-x-3">
                <span class="bg-white/20 p-2 rounded-xl group-hover:rotate-12 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
                <span class="text-lg">Absen Masuk</span>
            </button>
        </div>
        @yield('content')
    </main>

</div>

</body>
</html>