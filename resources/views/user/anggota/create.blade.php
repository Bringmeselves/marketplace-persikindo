@extends('layouts.app')

@section('title', 'Daftar Anggota')

@section('content')
    <h1>Daftar sebagai Anggota</h1>
    <form action="{{ route('user.anggota.store') }}" method="POST">
        @csrf
        <label for="name">Nama Lengkap:</label>
        <input type="text" name="name" required>
        <button type="submit">Daftar</button>
    </form>
@endsection
