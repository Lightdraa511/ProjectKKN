@extends('layouts.admin')

@section('title', 'Manajemen Mahasiswa - Admin')

@section('page-title', 'Manajemen Mahasiswa')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Mahasiswa</h1>
        <div>
            <a href="{{ route('admin.users.export', request()->query()) }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel fa-sm"></i> Export Excel
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Mahasiswa</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search">Cari (Nama/NIM/Email)</label>
                        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Cari...">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="faculty">Fakultas</label>
                        <select class="form-control" id="faculty" name="faculty">
                            <option value="">Semua Fakultas</option>
                            @foreach($faculties as $faculty)
                                <option value="{{ $faculty->id }}" {{ request('faculty') == $faculty->id ? 'selected' : '' }}>
                                    {{ $faculty->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="location">Lokasi KKN</label>
                        <select class="form-control" id="location" name="location">
                            <option value="">Semua Lokasi</option>
                            <option value="null" {{ request('location') === 'null' ? 'selected' : '' }}>Belum Memilih Lokasi</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ request('location') == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="location_selected" {{ request('status') === 'location_selected' ? 'selected' : '' }}>Lokasi Dipilih</option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Pembayaran Selesai</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Belum Bayar</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Result Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Mahasiswa</h6>
        </div>
        <div class="card-body">
            @if($users->isEmpty())
                <div class="alert alert-info">
                    Tidak ada data mahasiswa yang ditemukan.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Fakultas</th>
                                <th>Email</th>
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
                                <td>{{ $user->faculty->name ?? '-' }}</td>
                                <td>{{ $user->email }}</td>
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
                                        <span class="badge bg-info text-white">Lokasi Dipilih</span>
                                    @elseif($user->payment_status)
                                        <span class="badge bg-primary text-white">Pembayaran Selesai</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Belum Bayar</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus mahasiswa ini dari sistem?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable with pagination disabled (using Laravel's pagination)
        $('#dataTable').DataTable({
            "paging": false,
            "ordering": true,
            "searching": false,
            "info": false
        });
    });
</script>
@endsection
