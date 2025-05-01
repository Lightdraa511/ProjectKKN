@extends('layouts.app')

@section('title', 'Dashboard Admin - Sistem Pendaftaran KKN')

@section('navbar')
<div class="navbar">
    <h2>Dashboard Admin KKN</h2>
    <div class="user-info">
        <span>{{ Auth::guard('admin')->user()->name }}</span>
        <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
            @csrf
            <button type="submit" style="width: auto; padding: 5px 15px; margin: 0;">Logout</button>
        </form>
    </div>
</div>
@endsection

@section('content')
<div class="container">
    <h2>Pengelolaan Sistem Pendaftaran KKN</h2>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- Dashboard Stats -->
    <div class="dashboard-stats">
        <div class="stat-card">
            <h3>Total Lokasi KKN</h3>
            <div class="number">{{ $totalLocations }}</div>
        </div>
        <div class="stat-card">
            <h3>Total Pendaftar</h3>
            <div class="number">{{ $totalUsers }}</div>
        </div>
        <div class="stat-card">
            <h3>Pendaftar Selesai</h3>
            <div class="number">{{ $completedRegistrations }}</div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="tab-container">
        <div class="tab-header">
            <div class="tab active" data-tab="locations">Daftar Lokasi</div>
            <div class="tab" data-tab="faculties">Fakultas</div>
            <div class="tab" data-tab="statistics">Statistik</div>
            <div class="tab" data-tab="settings">Pengaturan</div>
            <div class="tab" data-tab="account">Akun Admin</div>
        </div>

        <!-- Daftar Lokasi Tab -->
        <div class="tab-content active" id="tab-locations">
            <div style="margin-bottom: 20px;">
                <a href="{{ route('admin.locations.create') }}" class="btn" style="width: auto; display: inline-block;">Tambah Lokasi Baru</a>
            </div>

            <div class="locations-grid">
                @foreach($locations ?? [] as $location)
                <div class="location-card">
                    <div class="location-name">{{ $location->name }}</div>
                    <div class="location-description">{{ $location->description }}</div>
                    <div class="quota-info">
                        <div class="faculty-quota" style="margin-bottom: 10px;">
                            <span><strong>Total Kuota</strong></span>
                            <span><strong>{{ $location->getAssignedCount() }}/{{ $location->total_quota }}</strong></span>
                        </div>
                    </div>
                    <div style="margin-top: 15px;">
                        <a href="{{ route('admin.locations.students', $location) }}" class="btn" style="background-color: #17a2b8; width: auto; display: inline-block; margin-right: 5px;">Lihat Daftar</a>
                        <a href="{{ route('admin.locations.edit', $location) }}" class="btn" style="background-color: #ffc107; width: auto; display: inline-block; margin-right: 5px;">Edit</a>
                        <form method="POST" action="{{ route('admin.locations.destroy', $location) }}" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn" style="background-color: #dc3545; width: auto;" onclick="return confirm('Yakin ingin menghapus lokasi ini?')">Hapus</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Fakultas Tab -->
        <div class="tab-content" id="tab-faculties">
            <div style="margin-bottom: 20px;">
                <a href="{{ route('admin.faculties.create') }}" class="btn" style="width: auto; display: inline-block;">Tambah Fakultas Baru</a>
            </div>

            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f1f1f1;">
                        <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Kode</th>
                        <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Nama Fakultas</th>
                        <th style="padding: 10px; text-align: center; border: 1px solid #ddd;">Jumlah Mahasiswa</th>
                        <th style="padding: 10px; text-align: center; border: 1px solid #ddd;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($faculties ?? [] as $faculty)
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd;">{{ $faculty->code }}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">{{ $faculty->name }}</td>
                        <td style="padding: 10px; text-align: center; border: 1px solid #ddd;">{{ $faculty->users_count }}</td>
                        <td style="padding: 10px; text-align: center; border: 1px solid #ddd;">
                            <a href="{{ route('admin.faculties.edit', $faculty) }}" class="btn" style="background-color: #ffc107; width: auto; display: inline-block; margin-right: 5px; padding: 5px 10px;">Edit</a>
                            <form method="POST" action="{{ route('admin.faculties.destroy', $faculty) }}" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn" style="background-color: #dc3545; width: auto; padding: 5px 10px;" onclick="return confirm('Yakin ingin menghapus fakultas ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Statistik Tab -->
        <div class="tab-content" id="tab-statistics">
            <h3>Statistik Pendaftaran</h3>

            <div class="dashboard-stats">
                <div class="stat-card">
                    <h3>Total Lokasi</h3>
                    <div class="number">{{ $totalLocations }}</div>
                </div>
                <div class="stat-card">
                    <h3>Total Pendaftar</h3>
                    <div class="number">{{ $totalUsers }}</div>
                </div>
                <div class="stat-card">
                    <h3>Pendaftar Selesai</h3>
                    <div class="number">{{ $completedRegistrations }}</div>
                </div>
            </div>

            <h3>Statistik per Fakultas</h3>
            <div style="margin-top: 20px;">
                @foreach($faculties ?? [] as $faculty)
                <div class="stat-card" style="margin-bottom: 10px;">
                    <h3>{{ $faculty->name }}</h3>
                    <div class="number">{{ $faculty->users_count }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Settings Tab -->
        <div class="tab-content" id="tab-settings">
            <h3>Pengaturan Umum KKN</h3>
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf

                <div class="form-group">
                    <label for="registrationStart">Tanggal Mulai Pendaftaran</label>
                    <input type="date" id="registrationStart" name="registration_start" value="{{ $registration_start ?? '' }}" required>
                </div>

                <div class="form-group">
                    <label for="registrationEnd">Tanggal Selesai Pendaftaran</label>
                    <input type="date" id="registrationEnd" name="registration_end" value="{{ $registration_end ?? '' }}" required>
                </div>

                <div class="form-group">
                    <label for="kknStart">Tanggal Mulai KKN</label>
                    <input type="date" id="kknStart" name="kkn_start" value="{{ $kkn_start ?? '' }}" required>
                </div>

                <div class="form-group">
                    <label for="kknEnd">Tanggal Selesai KKN</label>
                    <input type="date" id="kknEnd" name="kkn_end" value="{{ $kkn_end ?? '' }}" required>
                </div>

                <div class="form-group">
                    <label for="registrationFee">Biaya Pendaftaran (Rp)</label>
                    <input type="number" id="registrationFee" name="registration_fee" value="{{ $registration_fee ?? 500000 }}" required min="0">
                </div>

                <div class="form-group">
                    <label for="minSKS">Minimal SKS</label>
                    <input type="number" id="minSKS" name="min_sks" value="{{ $min_sks ?? 100 }}" required min="0">
                </div>

                <div class="form-group">
                    <label for="minGPA">Minimal IPK</label>
                    <input type="number" id="minGPA" name="min_gpa" value="{{ $min_gpa ?? 2.75 }}" required min="0" max="4" step="0.01">
                </div>

                <div class="form-group">
                    <label for="announcement">Pengumuman</label>
                    <textarea id="announcement" name="announcement" rows="4">{{ $announcement ?? '' }}</textarea>
                </div>

                <button type="submit">Simpan Pengaturan</button>
            </form>
        </div>

        <!-- Account Tab -->
        <div class="tab-content" id="tab-account">
            <h3>Manajemen Akun Admin</h3>
            <form method="POST" action="{{ route('admin.account.update') }}">
                @csrf

                <div class="form-group">
                    <label for="currentPassword">Password Saat Ini</label>
                    <input type="password" id="currentPassword" name="current_password" required>
                    @error('current_password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="newPassword">Password Baru</label>
                    <input type="password" id="newPassword" name="new_password" required>
                    @error('new_password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
                </div>

                <button type="submit">Ubah Password</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab navigation
        const tabs = document.querySelectorAll('.tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');

                // Update active tab
                document.querySelectorAll('.tab').forEach(t => {
                    t.classList.remove('active');
                });
                this.classList.add('active');

                // Show tab content
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                document.getElementById(`tab-${tabId}`).classList.add('active');
            });
        });
    });
</script>
@endsection
