@extends('layouts.dashboard')

@section('content')

<x-card title="Aktivitas Admin">
    <div class="text-gray-500 text-sm">
        Tidak ada aktivitas
    </div>
</x-card>

<x-card title="Kontrol Sistem">
    <button class="w-full bg-gray-300 py-2 rounded">
        Kelola User
    </button>
</x-card>

@endsection