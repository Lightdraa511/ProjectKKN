@extends('layouts.admin')

@section('title', 'Edit Mahasiswa - Admin')

@section('page-title', 'Edit Mahasiswa')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Mahasiswa: {{ $user->name }}</h1>
        <a href="{{ route('admin.users.show', $user) }}" class="d-none d-sm-inline-block btn btn-secondary shadow-sm">
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
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Mahasiswa</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="nim">NIM <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nim') is-invalid @enderror"
                                   id="nim" name="nim" value="{{ old('nim', $user->nim) }}" required>
                            @error('nim')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">Password Baru</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Minimal 8 karakter. Kosongkan jika tidak ingin mengubah password.
                            </small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="faculty_id">Fakultas <span class="text-danger">*</span></label>
                            <select class="form-control @error('faculty_id') is-invalid @enderror"
                                    id="faculty_id" name="faculty_id" required>
                                <option value="">Pilih Fakultas</option>
                                @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}" {{ (old('faculty_id', $user->faculty_id) == $faculty->id) ? 'selected' : '' }}>
                                        {{ $faculty->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('faculty_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="location_id">Lokasi KKN</label>
                            <select class="form-control @error('location_id') is-invalid @enderror"
                                    id="location_id" name="location_id">
                                <option value="">Belum Memilih Lokasi</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ (old('location_id', $user->location_id) == $location->id) ? 'selected' : '' }}>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('location_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                       id="payment_status" name="payment_status" value="1"
                                       {{ old('payment_status', $user->payment_status) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="payment_status">
                                    Status Pembayaran (centang jika sudah bayar)
                                </label>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                       id="profile_completed" name="profile_completed" value="1"
                                       {{ old('profile_completed', $user->profile_completed) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="profile_completed">
                                    Pendaftaran Selesai (centang jika sudah lengkap)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle"></i>
                    <strong>Catatan:</strong> Perubahan status pendaftaran akan mempengaruhi alur pendaftaran mahasiswa.
                    Pastikan data yang diubah sudah benar.
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
