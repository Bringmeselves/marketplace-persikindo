@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
<div class="max-w-xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-8 text-gray-800">

    <h2 class="text-3xl font-bold text-gray-900">Edit Kategori</h2>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="flex items-center gap-3 p-4 border-l-4 border-green-500 bg-green-50 rounded shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
            <span class="text-sm text-green-800 font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Form Edit Kategori --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 space-y-6">
        <form action="{{ route('admin.kategori.update', $kategori->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Input Nama --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name', $kategori->name) }}" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.kategori.index') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2 rounded-lg bg-gray-500 hover:bg-gray-600 text-white text-sm font-semibold transition">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    Batal
                </a>

                <button type="submit" 
                        class="inline-flex items-center gap-2 px-5 py-2 rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold transition">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Lucide Icons --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endsection
