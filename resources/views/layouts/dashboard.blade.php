@extends('layouts.app')

@section('content')
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
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </span>
            <span class="text-lg">Absen Masuk</span>
        </button>
    </div>
@endsection