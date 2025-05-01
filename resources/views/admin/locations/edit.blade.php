@extends('layouts.admin')

@section('title', 'Edit Lokasi KKN - Admin')

@section('page-title', 'Edit Lokasi KKN')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Lokasi: {{ $location->name }}</h1>
        <a href="{{ route('admin.locations.index') }}" class="d-none d-sm-inline-block btn btn-secondary shadow-sm">
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
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Lokasi</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.locations.update', $location) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nama Lokasi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $location->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi Lokasi <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description" name="description" rows="4" required>{{ old('description', $location->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="total_quota">Total Kuota <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('total_quota') is-invalid @enderror"
                           id="total_quota" name="total_quota" value="{{ old('total_quota', $location->total_quota) }}" min="1" required>
                    @error('total_quota')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Kuota per Fakultas</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">Tetapkan kuota untuk setiap fakultas (total kuota fakultas sebaiknya sama dengan total kuota lokasi)</p>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Fakultas</th>
                                        <th style="width: 180px;">Kuota</th>
                                        <th style="width: 180px;">Terdaftar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($faculties as $faculty)
                                    <tr>
                                        <td>{{ $faculty->name }}</td>
                                        <td>
                                            <input type="number" class="form-control faculty-quota"
                                                   name="faculty_quotas[{{ $faculty->id }}]"
                                                   value="{{ old('faculty_quotas.'.$faculty->id, $facultyQuotas[$faculty->id] ?? 0) }}"
                                                   min="0">
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $registeredCount = App\Models\User::where('location_id', $location->id)
                                                    ->where('faculty_id', $faculty->id)
                                                    ->count();
                                            @endphp
                                            {{ $registeredCount }}
                                            @if($registeredCount > 0)
                                                <div class="text-danger small">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    Kuota minimal harus {{ $registeredCount }}
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light">
                                        <th>Total</th>
                                        <th>
                                            <span id="faculty-quota-total">0</span>
                                        </th>
                                        <th>
                                            @php
                                                $totalRegistered = App\Models\User::where('location_id', $location->id)->count();
                                            @endphp
                                            {{ $totalRegistered }}
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="alert alert-info mt-3">
                            <strong>Catatan:</strong> Kuota 0 berarti tidak ada mahasiswa dari fakultas tersebut yang dapat memilih lokasi ini.
                            Anda tidak dapat mengurangi kuota di bawah jumlah mahasiswa yang sudah terdaftar.
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Calculate total faculty quota
    $(document).ready(function() {
        function calculateTotal() {
            let total = 0;
            $('.faculty-quota').each(function() {
                total += parseInt($(this).val()) || 0;
            });
            $('#faculty-quota-total').text(total);

            // Check if total matches
            const totalQuota = parseInt($('#total_quota').val()) || 0;
            if (total !== totalQuota && total > 0 && totalQuota > 0) {
                $('#faculty-quota-total').addClass('text-danger');
            } else {
                $('#faculty-quota-total').removeClass('text-danger');
            }
        }

        $('.faculty-quota, #total_quota').on('change keyup', calculateTotal);
        calculateTotal();
    });
</script>
@endsection
