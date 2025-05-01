@extends('layouts.admin')

@section('title', 'Tambah Fakultas - Admin')

@section('page-title', 'Tambah Fakultas')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Fakultas Baru</h1>
        <a href="{{ route('admin.faculties.index') }}" class="d-none d-sm-inline-block btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Fakultas</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.faculties.store') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label for="name">Nama Fakultas <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="code">Kode Fakultas <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror"
                           id="code" name="code" value="{{ old('code') }}" required
                           placeholder="contoh: FMIPA, FIB, FH, dll">
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        Kode fakultas biasanya berupa singkatan dari nama fakultas, dan akan diubah menjadi huruf kapital secara otomatis.
                    </small>
                </div>

                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle"></i>
                    <strong>Catatan:</strong> Setelah fakultas dibuat, Anda dapat mengatur kuota fakultas tersebut untuk setiap lokasi KKN melalui menu Lokasi KKN.
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Fakultas
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
