<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | APTAA System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex flex-col items-center justify-center p-6">

    <header class="mb-8 text-center">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight uppercase">
            APTAA <span class="text-blue-600">MANAGEMENT SYSTEM</span>
        </h1>
    </header>

    <div class="bg-white/80 backdrop-blur-xl w-full max-w-md p-10 rounded-[2.5rem] shadow-2xl shadow-blue-200/50 border border-white">
        
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-slate-800">Lupa Password?</h2>
            <p class="text-slate-500 mt-3 text-sm leading-relaxed">
                Masukkan alamat email Anda untuk mengatur ulang password.
            </p>
        </div>

        @if (session('status'))
            <div class="mb-6 font-medium text-sm text-green-600 bg-green-50 p-4 rounded-2xl border border-green-100 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 mb-2 ml-1">Email Terdaftar</label>
                <div class="relative">
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="nama@perusahaan.com" 
                        class="w-full px-5 py-4 bg-blue-50/50 border @error('email') border-red-300 @else border-blue-100 @enderror rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-200 placeholder:text-slate-400"
                        required autofocus>
                    <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                @error('email')
                    <p class="mt-1 text-xs text-red-500 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" 
                    class="w-full bg-slate-900 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-900/20 transform active:scale-[0.98] transition-all duration-300">
                    Kirim Link Reset
                </button>
            </div>
        </form>

        <div class="mt-8 text-center">
            <a href="{{ route('login') }}" class="text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke halaman login
            </a>
        </div>
    </div>

</body>
</html>