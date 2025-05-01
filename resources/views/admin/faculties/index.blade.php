@extends('layouts.admin')

@section('title', 'Manajemen Fakultas - Admin')

@section('page-title', 'Manajemen Fakultas')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Fakultas</h1>
        <a href="{{ route('admin.faculties.create') }}" class="d-none d-sm-inline-block btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Fakultas Baru
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Fakultas</h6>
        </div>
        <div class="card-body">
            @if($faculties->isEmpty())
                <div class="alert alert-info">
                    Belum ada fakultas yang tersedia. Klik tombol "Tambah Fakultas Baru" untuk membuat fakultas baru.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama Fakultas</th>
                                <th>Jumlah Mahasiswa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($faculties as $faculty)
                            <tr>
                                <td>{{ $faculty->id }}</td>
                                <td><strong>{{ $faculty->code }}</strong></td>
                                <td>{{ $faculty->name }}</td>
                                <td>{{ $faculty->users_count }}</td>
                                <td>
                                    <a href="{{ route('admin.faculties.show', $faculty) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('admin.faculties.edit', $faculty) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.faculties.destroy', $faculty) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus fakultas ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
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
