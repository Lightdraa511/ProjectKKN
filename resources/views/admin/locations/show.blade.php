@extends('layouts.admin')

@section('title', 'Detail Lokasi KKN - Admin')

@section('page-title', 'Detail Lokasi KKN')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Lokasi: {{ $location->name }}</h1>
        <div>
            <a href="{{ route('admin.locations.edit', $location) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit fa-sm"></i> Edit Lokasi
            </a>
            <a href="{{ route('admin.locations.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left fa-sm"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informasi Lokasi -->
        <div class="col-xl-6 col-md-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Lokasi</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 200px;">ID Lokasi</th>
                                <td>{{ $location->id }}</td>
                            </tr>
                            <tr>
                                <th>Nama Lokasi</th>
                                <td>{{ $location->name }}</td>
                            </tr>
                            <tr>
                                <th>Total Kuota</th>
                                <td>{{ $location->total_quota }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah Pendaftar</th>
                                <td>{{ $location->users_count ?? 0 }}</td>
                            </tr>
                            <tr>
                                <th>Dibuat Pada</th>
                                <td>{{ $location->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Diperbarui Pada</th>
                                <td>{{ $location->updated_at->format('d M Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="mt-4">
                        <h6 class="font-weight-bold">Deskripsi:</h6>
                        <p class="mt-2">{{ $location->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kuota Fakultas -->
        <div class="col-xl-6 col-md-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Kuota per Fakultas</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Fakultas</th>
                                    <th>Kuota</th>
                                    <th>Terdaftar</th>
                                    <th>Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($location->facultyQuotas as $facultyQuota)
                                <tr>
                                    <td>{{ $facultyQuota->faculty->name }}</td>
                                    <td>{{ $facultyQuota->quota }}</td>
                                    <td>
                                        @php
                                            $registeredCount = isset($registeredUsers[$facultyQuota->faculty_id])
                                                ? $registeredUsers[$facultyQuota->faculty_id]->count()
                                                : 0;
                                        @endphp
                                        {{ $registeredCount }}
                                    </td>
                                    <td>
                                        @php
                                            $percentage = $facultyQuota->quota > 0
                                                ? ($registeredCount / $facultyQuota->quota) * 100
                                                : 0;
                                        @endphp
                                        <div class="progress">
                                            <div class="progress-bar {{ $percentage >= 90 ? 'bg-danger' : ($percentage >= 70 ? 'bg-warning' : 'bg-success') }}"
                                                 role="progressbar" style="width: {{ $percentage }}%"
                                                 aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ number_format($percentage, 0) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada kuota fakultas yang ditetapkan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Mahasiswa -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Mahasiswa Terdaftar</h6>
        </div>
        <div class="card-body">
            @php
                $users = collect();
                foreach($registeredUsers as $facultyUsers) {
                    $users = $users->merge($facultyUsers);
                }
            @endphp

            @if($users->isEmpty())
                <div class="alert alert-info">
                    Belum ada mahasiswa yang terdaftar di lokasi ini.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Fakultas</th>
                                <th>Status</th>
                                <th>Tanggal Daftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->nim }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->faculty->name ?? '-' }}</td>
                                <td>
                                    @if($user->profile_completed)
                                        <span class="badge bg-success text-white">Selesai</span>
                                    @elseif($user->location_id)
                                        <span class="badge bg-info text-white">Proses</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Belum Lengkap</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
@endsection
