<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex flex-col items-center justify-center p-6">

    <header class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-slate-800 tracking-tight uppercase">
            {{ config('app.name') }}
        </h1>
        <p class="text-blue-600 text-xs mt-1 uppercase tracking-[0.2em] font-bold">Management System</p>
    </header>

    <div class="bg-white/80 backdrop-blur-xl w-full max-w-md p-10 rounded-[2.5rem] shadow-2xl shadow-blue-200/50 border border-white">
        
        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-xl border border-green-100">
                {{ session('status') }}
            </div>
        @endif

        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-slate-800">Selamat Datang</h2>
            <p class="text-slate-500 mt-2 text-sm">Silahkan masuk ke akun Anda</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 mb-2 ml-1">Email</label>
                <div class="relative">
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="Masukkan email..." 
                        class="w-full px-5 py-4 bg-blue-50/50 border @error('email') border-red-300 @else border-blue-100 @enderror rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-200 placeholder:text-slate-400"
                        required autofocus>
                    <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
                @error('email')
                    <p class="mt-1 text-xs text-red-500 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex justify-between items-center mb-2 ml-1">
                    <label for="password" class="text-sm font-semibold text-slate-700">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors">
                            Lupa Password?
                        </a>
                    @endif
                </div>
                <div class="relative">
                    <input type="password" id="password" name="password" 
                        placeholder="••••••••" 
                        class="w-full px-5 py-4 bg-blue-50/50 border @error('password') border-red-300 @else border-blue-100 @enderror rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-200"
                        required autocomplete="current-password">
                    <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                </div>
                @error('password')
                    <p class="mt-1 text-xs text-red-500 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center ml-1">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-blue-200 text-blue-600 shadow-sm focus:ring-blue-500">
                <label for="remember_me" class="ml-2 text-sm text-slate-500">Ingat perangkat ini</label>
            </div>

            <div class="pt-2">
                <button type="submit" 
                    class="w-full bg-slate-900 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-900/20 transform active:scale-[0.98] transition-all duration-300">
                    Masuk
                </button>
            </div>
        </form>

        <div class="mt-8 text-center text-[10px] text-slate-400 uppercase tracking-widest">
            &copy; 2026 aptaa v1.2
        </div>
    </div>

</body>
</html>