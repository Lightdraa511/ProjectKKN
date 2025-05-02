@extends('layouts.auth')

@section('title', 'Login - Sistem Pendaftaran KKN')

@section('content')
<div class="container">
    <h1>Login Mahasiswa</h1>

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

    <div class="alert alert-info">
        Selamat datang di Sistem Pendaftaran KKN. Silakan login untuk melanjutkan pendaftaran.
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="nim">NIM</label>
            <input type="text" id="nim" name="nim" value="{{ old('nim') }}" required>
            @error('nim')
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
            <a href="{{ route('register') }}">Belum memiliki akun? Daftar</a>
            <br><br>
            <a href="{{ route('admin.login') }}" style="color: #dc3545;">Login sebagai Admin</a>
            <br><br>
            <a href="{{ route('landing') }}" class="back-to-home">← Kembali ke Beranda</a>
        </div>
    </form>
</div>
@endsection
