@extends('layouts.app')

@section('title', 'Dashboard - Sistem Pendaftaran KKN')

@section('navbar')
<div class="navbar">
    <h2>Sistem Pendaftaran KKN</h2>
    <div class="user-info">
        <span>{{ auth()->user()->name }} ({{ auth()->user()->faculty->name }})</span>
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" style="width: auto; padding: 5px 15px; margin: 0;">Logout</button>
        </form>
    </div>
</div>
@endsection

@section('content')
<div class="container">
    <!-- Progress Steps -->
    <div class="steps">
        <div class="step {{ $currentStep >= 1 ? 'active' : '' }} {{ $currentStep > 1 ? 'completed' : '' }}" id="step1">
            <div class="step-number">1</div>
            <div class="step-title">Dashboard</div>
        </div>
        <div class="step {{ $currentStep >= 2 ? 'active' : '' }} {{ $currentStep > 2 ? 'completed' : '' }}" id="step2">
            <div class="step-number">2</div>
            <div class="step-title">Pembayaran</div>
        </div>
        <div class="step {{ $currentStep >= 3 ? 'active' : '' }} {{ $currentStep > 3 ? 'completed' : '' }}" id="step3">
            <div class="step-number">3</div>
            <div class="step-title">Pilih Lokasi</div>
        </div>
        <div class="step {{ $currentStep >= 4 ? 'active' : '' }} {{ $currentStep > 4 ? 'completed' : '' }}" id="step4">
            <div class="step-number">4</div>
            <div class="step-title">Data Diri</div>
        </div>
        <div class="step {{ $currentStep >= 5 ? 'active' : '' }}" id="step5">
            <div class="step-number">5</div>
            <div class="step-title">Selesai</div>
        </div>
    </div>

    <h2>Dashboard KKN</h2>

    <!-- Status Message -->
    <div class="alert {{ $statusClass }}">
        {{ $statusMessage }}
    </div>

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
            <h3>Status Pendaftaran</h3>
            <div>
                {{ $registrationStatus }}
                @if(auth()->user()->registration_status == 'pending')
                    <span class="badge badge-danger">Belum Bayar</span>
                @elseif(auth()->user()->registration_status == 'payment_completed')
                    <span class="badge badge-warning">Belum Lengkap</span>
                @elseif(auth()->user()->registration_status == 'location_selected')
                    <span class="badge badge-warning">Belum Lengkap</span>
                @elseif(auth()->user()->registration_status == 'completed')
                    <span class="badge badge-success">Selesai</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="tab-container">
        <div class="tab-header">
            <div class="tab active" data-tab="info">Informasi KKN</div>
            <div class="tab" data-tab="locations">Lokasi Tersedia</div>
            <div class="tab" data-tab="timeline">Timeline KKN</div>
        </div>

        <div class="tab-content active" id="tab-info">
            <h3>Informasi Program KKN</h3>

            @if(auth()->user()->profile_completed)
                <div class="alert alert-success">
                    <p><strong>Status:</strong> Pendaftaran Selesai</p>
                    @if(auth()->user()->location)
                        <p><strong>Lokasi KKN:</strong> {{ auth()->user()->location->name }}</p>
                    @endif
                    <p><strong>Pelaksanaan:</strong> 1 Juli - 31 Agustus 2025</p>
                </div>
                <p><strong>Langkah Selanjutnya:</strong></p>
                <ul>
                    <li>Harap menghadiri pembekalan KKN pada tanggal 15 - 20 Juni 2025</li>
                    <li>Cek email Anda secara berkala untuk informasi lebih lanjut</li>
                    <li>Persiapkan diri untuk pelaksanaan KKN pada tanggal 1 Juli 2025</li>
                </ul>
            @else
                <p>Kuliah Kerja Nyata (KKN) adalah program wajib bagi mahasiswa sebagai bentuk pengabdian kepada masyarakat. Program ini berlangsung selama 2 bulan di lokasi yang telah ditentukan.</p>
                <p><strong>Persyaratan:</strong></p>
                <ul>
                    <li>Telah menyelesaikan minimal 100 SKS</li>
                    <li>IPK minimal 2.75</li>
                    <li>Telah melunasi biaya pendaftaran KKN sebesar Rp 500.000</li>
                </ul>
                <p><strong>Jadwal Pelaksanaan:</strong></p>
                <ul>
                    <li>Pendaftaran: 1 - 30 Mei 2025</li>
                    <li>Pembekalan: 15 - 20 Juni 2025</li>
                    <li>Pelaksanaan: 1 Juli - 31 Agustus 2025</li>
                </ul>

                @if(!auth()->user()->payment_status)
                    <div class="alert alert-warning">
                        <strong>Perhatian!</strong> Anda harus melunasi pembayaran terlebih dahulu sebelum dapat memilih lokasi KKN.
                    </div>
                    <a href="{{ route('payment.index') }}" class="btn">Lanjut ke Pembayaran</a>
                @endif
            @endif
        </div>

        <div class="tab-content" id="tab-locations">
            <h3>Lokasi KKN Tersedia</h3>
            <p>Berikut adalah lokasi-lokasi KKN yang tersedia.
            @if(!auth()->user()->payment_status)
                Anda harus melunasi pembayaran terlebih dahulu sebelum dapat memilih lokasi.
            @elseif(!auth()->user()->location_id)
                Silakan pilih lokasi KKN yang tersedia.
            @endif
            </p>

            <div class="locations-grid">
                @foreach($locations as $location)
                <div class="location-card">
                    <div class="location-name">{{ $location->name }}</div>
                    <div class="location-description">{{ $location->description }}</div>
                    <div class="quota-info">
                        <div class="faculty-quota" style="margin-bottom: 10px;">
                            <span><strong>Total Kuota</strong></span>
                            <span><strong>{{ $location->getAssignedCount() }}/{{ $location->total_quota }}</strong></span>
                        </div>
                        <div style="font-weight: bold; margin-bottom: 5px;">Kuota per Fakultas:</div>

                        @foreach($location->facultyQuotas as $quota)
                        <div class="faculty-quota">
                            <span>{{ $quota->faculty->name }}</span>
                            <span style="color: {{ $quota->isFull() ? 'red' : 'green' }}">
                                {{ $quota->getAssignedCount() }}/{{ $quota->quota }}
                            </span>
                        </div>
                        @endforeach
                    </div>

                    @if(!auth()->user()->payment_status)
                        <button class="select-btn" disabled style="background-color: #999;">
                            Selesaikan Pembayaran
                        </button>
                    @elseif(auth()->user()->location_id)
                        <button class="select-btn" disabled style="background-color: #999;">
                            Anda Sudah Memilih Lokasi
                        </button>
                    @else
                        <a href="{{ route('locations.select', $location) }}" class="btn select-btn"
                           style="{{ $location->hasAvailableQuota(auth()->user()->faculty_id) ? '' : 'background-color: #999;' }}"
                           {{ $location->hasAvailableQuota(auth()->user()->faculty_id) ? '' : 'disabled' }}>
                            {{ $location->hasAvailableQuota(auth()->user()->faculty_id) ? 'Pilih Lokasi' : 'Kuota Fakultas Penuh' }}
                        </a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <div class="tab-content" id="tab-timeline">
            <h3>Timeline KKN</h3>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-icon">1</div>
                    <div class="timeline-date">1 - 30 Mei 2025</div>
                    <div class="timeline-content">
                        <h4>Pendaftaran KKN</h4>
                        <p>Pembayaran biaya KKN dan pemilihan lokasi.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-icon">2</div>
                    <div class="timeline-date">1 - 10 Juni 2025</div>
                    <div class="timeline-content">
                        <h4>Pengumuman Kelompok</h4>
                        <p>Pengumuman pembagian kelompok KKN.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-icon">3</div>
                    <div class="timeline-date">15 - 20 Juni 2025</div>
                    <div class="timeline-content">
                        <h4>Pembekalan KKN</h4>
                        <p>Pembekalan wajib bagi seluruh peserta KKN.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-icon">4</div>
                    <div class="timeline-date">1 Juli - 31 Agustus 2025</div>
                    <div class="timeline-content">
                        <h4>Pelaksanaan KKN</h4>
                        <p>Pelaksanaan program KKN di lokasi masing-masing.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-icon">5</div>
                    <div class="timeline-date">10 - 20 September 2025</div>
                    <div class="timeline-content">
                        <h4>Pengumpulan Laporan</h4>
                        <p>Batas akhir pengumpulan laporan KKN.</p>
                    </div>
                </div>
            </div>
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
