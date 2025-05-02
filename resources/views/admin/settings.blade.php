@extends('layouts.admin')

@section('title', 'Pengaturan Sistem - Admin KKN')

@section('page-title', 'Pengaturan Sistem')

@section('content')
<!-- Settings Form -->
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Pengaturan Umum KKN</h6>
                <div class="badge {{ $isRegistrationOpen ? 'badge-success' : 'badge-danger' }}">
                    {{ $isRegistrationOpen ? 'Pendaftaran Dibuka' : 'Pendaftaran Ditutup' }}
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf

                    <div class="form-group row">
                        <label for="registration_start" class="col-sm-4 col-form-label">Tanggal Mulai Pendaftaran</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control @error('registration_start') is-invalid @enderror"
                                id="registration_start" name="registration_start"
                                value="{{ old('registration_start', $registration_start ?? '') }}" required>
                            @error('registration_start')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="registration_end" class="col-sm-4 col-form-label">Tanggal Selesai Pendaftaran</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control @error('registration_end') is-invalid @enderror"
                                id="registration_end" name="registration_end"
                                value="{{ old('registration_end', $registration_end ?? '') }}" required>
                            @error('registration_end')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="kkn_start" class="col-sm-4 col-form-label">Tanggal Mulai KKN</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control @error('kkn_start') is-invalid @enderror"
                                id="kkn_start" name="kkn_start"
                                value="{{ old('kkn_start', $kkn_start ?? '') }}" required>
                            @error('kkn_start')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="kkn_end" class="col-sm-4 col-form-label">Tanggal Selesai KKN</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control @error('kkn_end') is-invalid @enderror"
                                id="kkn_end" name="kkn_end"
                                value="{{ old('kkn_end', $kkn_end ?? '') }}" required>
                            @error('kkn_end')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label for="registration_fee" class="col-sm-4 col-form-label">Biaya Pendaftaran (Rp)</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control @error('registration_fee') is-invalid @enderror"
                                id="registration_fee" name="registration_fee"
                                value="{{ old('registration_fee', $registration_fee ?? 500000) }}" required min="0">
                            @error('registration_fee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="min_sks" class="col-sm-4 col-form-label">Minimal SKS</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control @error('min_sks') is-invalid @enderror"
                                id="min_sks" name="min_sks"
                                value="{{ old('min_sks', $min_sks ?? 100) }}" required min="0">
                            @error('min_sks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="min_gpa" class="col-sm-4 col-form-label">Minimal IPK</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control @error('min_gpa') is-invalid @enderror"
                                id="min_gpa" name="min_gpa"
                                value="{{ old('min_gpa', $min_gpa ?? 2.75) }}" required min="0" max="4" step="0.01">
                            @error('min_gpa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label for="announcement">Pengumuman</label>
                        <textarea class="form-control @error('announcement') is-invalid @enderror"
                            id="announcement" name="announcement" rows="5">{{ old('announcement', $announcement ?? '') }}</textarea>
                        <small class="form-text text-muted">Pengumuman ini akan ditampilkan di dashboard mahasiswa.</small>
                        @error('announcement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                    <div class="progress mb-4">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ ($totalUsers > 0) ? (($completedRegistrations / $totalUsers) * 100) : 0 }}%"
                            aria-valuenow="{{ $completedRegistrations ?? 0 }}" aria-valuemin="0" aria-valuemax="{{ $totalUsers ?? 0 }}"></div>
                    </div>
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
                            @if($isRegistrationOpen)
                                <span class="badge badge-success">Dibuka</span>
                            @else
                                <span class="badge badge-danger">Ditutup</span>
                            @endif
                        </span>
                    </h5>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    @if($isRegistrationOpen)
                        Pendaftaran KKN sedang dibuka
                    @else
                        @if(!empty($registration_start) && \Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($registration_start)))
                            Pendaftaran KKN akan dibuka pada {{ \Carbon\Carbon::parse($registration_start)->format('d F Y') }}
                        @else
                            Pendaftaran KKN telah ditutup
                        @endif
                    @endif
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
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle"></i> Perubahan waktu pendaftaran akan langsung berpengaruh pada sistem.
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Script tambahan jika diperlukan
    $(document).ready(function() {
        // Formatting input if needed
    });
</script>
@endsection
