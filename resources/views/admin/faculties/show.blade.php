@extends('layouts.admin')

@section('title', 'Detail Fakultas - Admin')

@section('page-title', 'Detail Fakultas')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Fakultas: {{ $faculty->name }}</h1>
        <div>
            <a href="{{ route('admin.faculties.edit', $faculty) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit fa-sm"></i> Edit Fakultas
            </a>
            <a href="{{ route('admin.faculties.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left fa-sm"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informasi Fakultas -->
        <div class="col-xl-6 col-md-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Fakultas</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 200px;">ID Fakultas</th>
                                <td>{{ $faculty->id }}</td>
                            </tr>
                            <tr>
                                <th>Kode Fakultas</th>
                                <td><strong>{{ $faculty->code }}</strong></td>
                            </tr>
                            <tr>
                                <th>Nama Fakultas</th>
                                <td>{{ $faculty->name }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah Mahasiswa</th>
                                <td>{{ $faculty->users_count }}</td>
                            </tr>
                            <tr>
                                <th>Dibuat Pada</th>
                                <td>{{ $faculty->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Diperbarui Pada</th>
                                <td>{{ $faculty->updated_at->format('d M Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="col-xl-6 col-md-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Fakultas</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="font-weight-bold">Jumlah Mahasiswa Terdaftar</h6>
                        <h1 class="display-4 text-primary">{{ $faculty->users_count }}</h1>
                    </div>

                    <div class="mb-4">
                        <h6 class="font-weight-bold">Distribusi Mahasiswa per Lokasi</h6>
                        @php
                            $locationStats = App\Models\User::where('faculty_id', $faculty->id)
                                ->whereNotNull('location_id')
                                ->selectRaw('location_id, count(*) as count')
                                ->groupBy('location_id')
                                ->with('location')
                                ->get();
                        @endphp

                        @if($locationStats->isEmpty())
                            <div class="alert alert-info">
                                Belum ada mahasiswa yang memilih lokasi KKN.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Lokasi</th>
                                            <th>Jumlah Mahasiswa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($locationStats as $stat)
                                        <tr>
                                            <td>
                                                @if($stat->location)
                                                    <a href="{{ route('admin.locations.show', $stat->location) }}">
                                                        {{ $stat->location->name }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Lokasi Tidak Tersedia</span>
                                                @endif
                                            </td>
                                            <td>{{ $stat->count }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Mahasiswa -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Mahasiswa dari Fakultas {{ $faculty->name }}</h6>
        </div>
        <div class="card-body">
            @if($users->isEmpty())
                <div class="alert alert-info">
                    Belum ada mahasiswa yang terdaftar dari fakultas ini.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Lokasi KKN</th>
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
                                <td>
                                    @if($user->location)
                                        <a href="{{ route('admin.locations.show', $user->location) }}">
                                            {{ $user->location->name }}
                                        </a>
                                    @else
                                        <span class="badge bg-secondary text-white">Belum Memilih</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->profile_completed)
                                        <span class="badge bg-success text-white">Selesai</span>
                                    @elseif($user->location_id)
                                        <span class="badge bg-info text-white">Proses</span>
                                    @elseif($user->payment_status)
                                        <span class="badge bg-primary text-white">Sudah Bayar</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Belum Bayar</span>
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

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "paging": false,
            "searching": true,
            "ordering": true,
            "info": false,
        });
    });
</script>
@endsection
