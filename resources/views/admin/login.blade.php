@extends('layouts.app')

@section('title', 'Admin Login - Sistem Pendaftaran KKN')

@section('content')
<div class="container">
    <h1>Login Admin KKN</h1>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="form-group">
        <div class="alert alert-info">
            Silakan login sebagai admin untuk mengelola sistem KKN.
        </div>
    </div>

    <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}" required>
            @error('username')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            @error('password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">Masuk</button>

        <div class="links">
            <a href="{{ route('login') }}">Login sebagai Mahasiswa</a>
        </div>
    </form>
</div>
@endsection
