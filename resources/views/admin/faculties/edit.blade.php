@extends('layouts.admin')

@section('title', 'Edit Fakultas - Admin')

@section('page-title', 'Edit Fakultas')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Fakultas: {{ $faculty->name }}</h1>
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
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Fakultas</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.faculties.update', $faculty) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label for="name">Nama Fakultas <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $faculty->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="code">Kode Fakultas <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror"
                           id="code" name="code" value="{{ old('code', $faculty->code) }}" required>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        Kode fakultas biasanya berupa singkatan dari nama fakultas, dan akan diubah menjadi huruf kapital secara otomatis.
                    </small>
                </div>

                @php
                    $userCount = App\Models\User::where('faculty_id', $faculty->id)->count();
                @endphp
                @if($userCount > 0)
                <div class="alert alert-warning mb-4">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Perhatian:</strong> Fakultas ini sudah digunakan oleh {{ $userCount }} mahasiswa.
                    Perubahan yang Anda lakukan akan berdampak pada data tersebut.
                </div>
                @endif

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
