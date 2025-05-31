@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-semibold mb-4">Dashboard</h2>
    <p>Selamat datang, {{ auth()->user()->name }}!</p>
@endsection
