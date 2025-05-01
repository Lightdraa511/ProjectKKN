@extends('layouts.admin')

@section('title', 'Detail Mahasiswa - Admin')

@section('page-title', 'Detail Mahasiswa')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Mahasiswa: {{ $user->name }}</h1>
        <div>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit fa-sm"></i> Edit Data
            </a>
            <form method="POST" action="{{ route('admin.users.reset-progress', $user) }}" class="d-inline" onsubmit="return confirm('Yakin ingin mereset progres pendaftaran mahasiswa ini?')">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fas fa-undo fa-sm"></i> Reset Progres
                </button>
            </form>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left fa-sm"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informasi Mahasiswa -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Mahasiswa</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 200px;">NIM</th>
                                <td>{{ $user->nim }}</td>
                            </tr>
                            <tr>
                                <th>Nama Lengkap</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Fakultas</th>
                                <td>{{ $user->faculty->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Daftar</th>
                                <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Status Pembayaran</th>
                                <td>
                                    @if($user->payment_status)
                                        <span class="badge bg-success text-white">Sudah Bayar</span>
                                    @else
                                        <span class="badge bg-danger text-white">Belum Bayar</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Lokasi KKN</th>
                                <td>
                                    @if($user->location)
                                        <a href="{{ route('admin.locations.show', $user->location) }}">
                                            {{ $user->location->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">Belum memilih lokasi</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Status Pendaftaran</th>
                                <td>
                                    @if($user->profile_completed)
                                        <span class="badge bg-success text-white">Selesai</span>
                                    @elseif($user->location_id)
                                        <span class="badge bg-info text-white">Lokasi Dipilih</span>
                                    @elseif($user->payment_status)
                                        <span class="badge bg-primary text-white">Pembayaran Selesai</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Belum Bayar</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status dan Data Profile -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status Pendaftaran</h6>
                </div>
                <div class="card-body">
                    <div class="progress-steps">
                        <div class="step {{ $user->registration_status != 'registered' ? 'completed' : 'active' }}">
                            <div class="step-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="step-content">
                                <h5>Pendaftaran</h5>
                                <p>{{ $user->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="step {{ $user->payment_status ? 'completed' : '' }}">
                            <div class="step-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="step-content">
                                <h5>Pembayaran</h5>
                                <p>
                                    @if($user->payment_status)
                                        <span class="text-success">Pembayaran diterima</span>
                                    @else
                                        <span class="text-muted">Belum melakukan pembayaran</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="step {{ $user->location_id ? 'completed' : '' }}">
                            <div class="step-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="step-content">
                                <h5>Pemilihan Lokasi</h5>
                                <p>
                                    @if($user->location)
                                        <span class="text-success">{{ $user->location->name }}</span>
                                    @else
                                        <span class="text-muted">Belum memilih lokasi</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="step {{ $user->profile_completed ? 'completed' : '' }}">
                            <div class="step-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="step-content">
                                <h5>Data Diri</h5>
                                <p>
                                    @if($user->profile_completed)
                                        <span class="text-success">Data diri lengkap</span>
                                    @else
                                        <span class="text-muted">Belum mengisi data diri</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Profile -->
    @if($user->profile)
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Diri</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px;">Tempat Lahir</th>
                            <td>{{ $user->profile->birth_place }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Lahir</th>
                            <td>{{ $user->profile->birth_date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td>{{ $user->profile->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                        </tr>
                        <tr>
                            <th>Agama</th>
                            <td>{{ $user->profile->religion }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $user->profile->address }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px;">Nomor Telepon</th>
                            <td>{{ $user->profile->phone_number }}</td>
                        </tr>
                        <tr>
                            <th>Golongan Darah</th>
                            <td>{{ $user->profile->blood_type ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Kontak Darurat</th>
                            <td>{{ $user->profile->emergency_contact }}</td>
                        </tr>
                        <tr>
                            <th>Hubungan Kontak Darurat</th>
                            <td>{{ $user->profile->emergency_relation }}</td>
                        </tr>
                        <tr>
                            <th>Riwayat Penyakit</th>
                            <td>{{ $user->profile->medical_history ?? 'Tidak ada' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    .progress-steps {
        display: flex;
        flex-direction: column;
    }
    .step {
        display: flex;
        margin-bottom: 20px;
        opacity: 0.5;
    }
    .step.active, .step.completed {
        opacity: 1;
    }
    .step-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #f8f9fc;
        border: 2px solid #d1d3e2;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }
    .step.completed .step-icon {
        background-color: #4e73df;
        border-color: #4e73df;
        color: white;
    }
    .step.active .step-icon {
        border-color: #4e73df;
        color: #4e73df;
    }
    .step-content h5 {
        margin-bottom: 5px;
        font-size: 16px;
    }
    .step-content p {
        margin-bottom: 0;
        font-size: 14px;
        color: #6c757d;
    }
</style>
@endsection
