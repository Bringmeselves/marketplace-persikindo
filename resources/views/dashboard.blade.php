{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Welcome to the Dashboard</h1>
        <p>This is the homepage after login.</p>
        <p>Welcome, {{ Auth::user()->name }}!</p>
    </div>
@endsection
