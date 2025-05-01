@extends('layouts.admin')

@section('title', 'Dashboard Admin - Sistem Pendaftaran KKN')

@section('page-title', 'Dashboard Admin')

@section('content')
<div class="row">
    <!-- Total Lokasi KKN -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Lokasi KKN</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLocations }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-map-marker-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Pendaftar -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Pendaftar</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pendaftar Selesai -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Pendaftar Selesai</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completedRegistrations }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Daftar Lokasi -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Lokasi KKN</h6>
                <a href="{{ route('admin.locations.create') }}" class="btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Lokasi
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nama Lokasi</th>
                                <th>Kuota</th>
                                <th>Terisi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($locations ?? [] as $location)
                            <tr>
                                <td>{{ $location->name }}</td>
                                <td>{{ $location->total_quota }}</td>
                                <td>{{ $location->getAssignedCount() }}</td>
                                <td>
                                    <a href="{{ route('admin.locations.students', $location) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-users fa-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.locations.edit', $location) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit fa-sm"></i>
                                    </a>
                                    <form action="{{ route('admin.locations.destroy', $location) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus lokasi ini?')">
                                            <i class="fas fa-trash fa-sm"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Fakultas -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Fakultas</h6>
                <a href="{{ route('admin.faculties.create') }}" class="btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Fakultas
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Fakultas</th>
                                <th>Jumlah Mahasiswa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($faculties ?? [] as $faculty)
                            <tr>
                                <td>{{ $faculty->name }}</td>
                                <td class="text-center">{{ $faculty->users_count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pengaturan & Akun -->
<div class="row">
    <!-- Pengaturan -->
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Pengaturan Sistem</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="registrationStart">Tanggal Mulai Pendaftaran</label>
                                <input type="date" class="form-control" id="registrationStart" name="registration_start" value="{{ $registration_start ?? '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="registrationEnd">Tanggal Selesai Pendaftaran</label>
                                <input type="date" class="form-control" id="registrationEnd" name="registration_end" value="{{ $registration_end ?? '' }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kknStart">Tanggal Mulai KKN</label>
                                <input type="date" class="form-control" id="kknStart" name="kkn_start" value="{{ $kkn_start ?? '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kknEnd">Tanggal Selesai KKN</label>
                                <input type="date" class="form-control" id="kknEnd" name="kkn_end" value="{{ $kkn_end ?? '' }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="registrationFee">Biaya Pendaftaran (Rp)</label>
                                <input type="number" class="form-control" id="registrationFee" name="registration_fee" value="{{ $registration_fee ?? 500000 }}" required min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="minSKS">Minimal SKS</label>
                                <input type="number" class="form-control" id="minSKS" name="min_sks" value="{{ $min_sks ?? 100 }}" required min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="minGPA">Minimal IPK</label>
                                <input type="number" class="form-control" id="minGPA" name="min_gpa" value="{{ $min_gpa ?? 2.75 }}" required min="0" max="4" step="0.01">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="announcement">Pengumuman</label>
                        <textarea class="form-control" id="announcement" name="announcement" rows="3">{{ $announcement ?? '' }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save fa-sm"></i> Simpan Pengaturan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Akun -->
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Pengaturan Akun</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.account.update') }}">
                    @csrf
                    <div class="form-group">
                        <label for="currentPassword">Password Saat Ini</label>
                        <input type="password" class="form-control" id="currentPassword" name="current_password" required>
                        @error('current_password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="newPassword">Password Baru</label>
                        <input type="password" class="form-control" id="newPassword" name="new_password" required>
                        @error('new_password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-key fa-sm"></i> Ubah Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
