@extends('layouts.admin')

@section('title', 'Pengaturan Akun - Admin KKN')

@section('page-title', 'Pengaturan Akun Admin')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <!-- Account Settings -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Pengaturan Akun Admin</h6>
            </div>
            <div class="card-body">
                <!-- Account Information -->
                <div class="mb-4">
                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-circle bg-primary text-white mr-3">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</div>
                                    <div class="small text-gray-600">Username: {{ Auth::guard('admin')->user()->username ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="small text-gray-600">
                                <div><strong>Login Terakhir:</strong> {{ Auth::guard('admin')->user()->last_login_at ?? 'Belum ada data' }}</div>
                                <div><strong>IP Login Terakhir:</strong> {{ Auth::guard('admin')->user()->last_login_ip ?? 'Belum ada data' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Change Password Form -->
                <form method="POST" action="{{ route('admin.account.update') }}">
                    @csrf
                    <div class="form-group">
                        <label for="current_password">Password Saat Ini</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="new_password">Password Baru</label>
                        <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" required>
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Password minimal 8 karakter.</small>
                    </div>

                    <div class="form-group">
                        <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-key"></i> Ubah Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <!-- Security Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Keamanan Akun</h6>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h5 class="small font-weight-bold">Tips Keamanan Akun</h5>
                    <ul>
                        <li>Gunakan password yang kuat dengan kombinasi huruf, angka, dan simbol.</li>
                        <li>Jangan bagikan akun admin kepada orang yang tidak berwenang.</li>
                        <li>Ganti password secara berkala (disarankan setiap 3 bulan).</li>
                        <li>Pastikan untuk keluar (logout) setelah selesai menggunakan sistem.</li>
                        <li>Jangan mengakses panel admin dari komputer publik.</li>
                    </ul>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Jika Anda kesulitan mengakses akun, silahkan hubungi administrator sistem.
                </div>
            </div>
        </div>

        <!-- Activity Logs -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Aktivitas Login Terakhir</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>IP Address</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($loginLogs) && count($loginLogs) > 0)
                                @foreach($loginLogs as $log)
                                <tr>
                                    <td>{{ $log->created_at }}</td>
                                    <td>{{ $log->ip_address }}</td>
                                    <td>
                                        @if($log->status)
                                            <span class="badge badge-success">Sukses</span>
                                        @else
                                            <span class="badge badge-danger">Gagal</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data aktivitas login</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
