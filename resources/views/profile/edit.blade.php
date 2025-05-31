@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Profil</h2>

    @if (session('success'))
        <div class="mb-6 p-4 text-green-800 bg-green-100 border border-green-300 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Update Profil --}}
    <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PATCH')

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
            <input type="text" id="name" name="name" required
                value="{{ old('name', $user->name) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-300 focus:border-blue-500 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" required
                value="{{ old('email', $user->email) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-300 focus:border-blue-500 @error('email') border-red-500 @enderror">
            @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password Baru (opsional)</label>
            <input type="password" id="password" name="password"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-300 focus:border-blue-500 @error('password') border-red-500 @enderror">
            @error('password')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-300 focus:border-blue-500">
        </div>

        <div class="pt-4">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow transition duration-150">
                Perbarui Profil
            </button>
        </div>
    </form>

    <hr class="my-10 border-gray-300">

    {{-- Form Hapus Akun --}}
    <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
        @csrf
        @method('DELETE')

        <button type="submit"
            class="w-full inline-flex justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md shadow transition duration-150">
            Hapus Akun
        </button>
    </form>
</div>
@endsection
