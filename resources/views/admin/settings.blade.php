@extends('layouts.admin')

@section('title', 'Pengaturan Sistem - Admin KKN')

@section('page-title', 'Pengaturan Sistem')

@section('content')
<!-- Settings Form -->
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Pengaturan Umum KKN</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf

                    <div class="form-group row">
                        <label for="registration_start" class="col-sm-4 col-form-label">Tanggal Mulai Pendaftaran</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="registration_start" name="registration_start" value="{{ $registration_start ?? '' }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="registration_end" class="col-sm-4 col-form-label">Tanggal Selesai Pendaftaran</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="registration_end" name="registration_end" value="{{ $registration_end ?? '' }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="kkn_start" class="col-sm-4 col-form-label">Tanggal Mulai KKN</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="kkn_start" name="kkn_start" value="{{ $kkn_start ?? '' }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="kkn_end" class="col-sm-4 col-form-label">Tanggal Selesai KKN</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="kkn_end" name="kkn_end" value="{{ $kkn_end ?? '' }}" required>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label for="registration_fee" class="col-sm-4 col-form-label">Biaya Pendaftaran (Rp)</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="registration_fee" name="registration_fee" value="{{ $registration_fee ?? 500000 }}" required min="0">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="min_sks" class="col-sm-4 col-form-label">Minimal SKS</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="min_sks" name="min_sks" value="{{ $min_sks ?? 100 }}" required min="0">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="min_gpa" class="col-sm-4 col-form-label">Minimal IPK</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="min_gpa" name="min_gpa" value="{{ $min_gpa ?? 2.75 }}" required min="0" max="4" step="0.01">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label for="announcement">Pengumuman</label>
                        <textarea class="form-control" id="announcement" name="announcement" rows="5">{{ $announcement ?? '' }}</textarea>
                        <small class="form-text text-muted">Pengumuman ini akan ditampilkan di dashboard mahasiswa.</small>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Pengaturan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Sistem</h6>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h5 class="small font-weight-bold">Total Mahasiswa<span class="float-right">{{ $totalUsers ?? 0 }}</span></h5>
                </div>
                <div class="mb-4">
                    <h5 class="small font-weight-bold">Pendaftaran Selesai<span class="float-right">{{ $completedRegistrations ?? 0 }}</span></h5>
                </div>
                <div class="mb-4">
                    <h5 class="small font-weight-bold">Total Lokasi KKN<span class="float-right">{{ $totalLocations ?? 0 }}</span></h5>
                </div>
                <div class="mb-4">
                    <h5 class="small font-weight-bold">Status Pendaftaran
                        <span class="float-right">
                            @if(isset($registration_start) && isset($registration_end) && now()->between($registration_start, $registration_end))
                                <span class="badge badge-success">Dibuka</span>
                            @else
                                <span class="badge badge-danger">Ditutup</span>
                            @endif
                        </span>
                    </h5>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Bantuan</h6>
            </div>
            <div class="card-body">
                <p>Pengaturan ini akan mempengaruhi seluruh alur pendaftaran KKN.</p>
                <p>Pastikan data yang dimasukkan sudah benar sebelum menyimpan perubahan.</p>
                <a href="#" class="btn btn-info btn-block">
                    <i class="fas fa-question-circle"></i> Panduan Admin
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Script tambahan jika diperlukan
</script>
@endsection
