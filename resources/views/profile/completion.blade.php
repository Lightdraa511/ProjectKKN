@extends('layouts.user')

@section('title', 'Pendaftaran Selesai - Sistem Pendaftaran KKN')

@section('content')
<!-- Progress Steps -->
<div class="steps">
    <div class="step completed" id="step1">
        <div class="step-number">1</div>
        <div class="step-title">Dashboard</div>
    </div>
    <div class="step completed" id="step2">
        <div class="step-number">2</div>
        <div class="step-title">Pembayaran</div>
    </div>
    <div class="step completed" id="step3">
        <div class="step-number">3</div>
        <div class="step-title">Pilih Lokasi</div>
    </div>
    <div class="step completed" id="step4">
        <div class="step-number">4</div>
        <div class="step-title">Data Diri</div>
    </div>
    <div class="step active" id="step5">
        <div class="step-number">5</div>
        <div class="step-title">Selesai</div>
    </div>
</div>

<h2>Pendaftaran KKN Selesai</h2>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="completion-message">
    <div class="alert alert-success">
        <i class="icon-check"></i>
        <h3>Selamat! Pendaftaran KKN Anda Telah Selesai</h3>
        <p>Terima kasih telah menyelesaikan pendaftaran KKN. Data Anda telah tersimpan dalam sistem.</p>
    </div>

    <div class="registration-summary">
        <h3>Ringkasan Pendaftaran</h3>

        <div class="summary-section">
            <h4>Informasi Mahasiswa</h4>
            <div class="detail-item">
                <span>Nama:</span>
                <strong>{{ $user->name }}</strong>
            </div>
            <div class="detail-item">
                <span>NIM:</span>
                <strong>{{ $user->nim }}</strong>
            </div>
            <div class="detail-item">
                <span>Fakultas:</span>
                <strong>{{ $user->faculty->name }}</strong>
            </div>
            <div class="detail-item">
                <span>Email:</span>
                <strong>{{ $user->email }}</strong>
            </div>
        </div>

        <div class="summary-section">
            <h4>Lokasi KKN</h4>
            <div class="detail-item">
                <span>Lokasi:</span>
                <strong>{{ $location->name }}</strong>
            </div>
            @if(isset($location->address))
            <div class="detail-item">
                <span>Alamat:</span>
                <strong>{{ $location->address }}</strong>
            </div>
            @endif
            <div class="detail-item">
                <span>Deskripsi:</span>
                <strong>{{ $location->description }}</strong>
            </div>
        </div>

        <div class="summary-section">
            <h4>Data Profil</h4>
            <div class="detail-item">
                <span>Alamat:</span>
                <strong>{{ $profile->address }}</strong>
            </div>
            <div class="detail-item">
                <span>Telepon:</span>
                <strong>{{ $profile->phone }}</strong>
            </div>
            <div class="detail-item">
                <span>Kontak Darurat:</span>
                <strong>{{ $profile->emergency_contact }}</strong>
            </div>
            <div class="detail-item">
                <span>Golongan Darah:</span>
                <strong>{{ $profile->blood_type }}</strong>
            </div>
            @if($profile->disease_history)
            <div class="detail-item">
                <span>Riwayat Penyakit:</span>
                <strong>{{ $profile->disease_history }}</strong>
            </div>
            @endif
            <div class="detail-item">
                <span>Keahlian:</span>
                <strong>{{ $profile->skills }}</strong>
            </div>
        </div>
    </div>

    <div class="next-info">
        <h3>Informasi Selanjutnya</h3>
        <p>Silakan pantau terus pengumuman terkait pelaksanaan KKN melalui email atau dashboard ini. Informasi lebih lanjut akan disampaikan oleh koordinator KKN.</p>

        <div class="alert alert-info">
            <p><strong>Catatan Penting:</strong></p>
            <ul>
                <li>Pelaksanaan KKN dijadwalkan dari tanggal 1 Juli hingga 31 Agustus 2023</li>
                <li>Pembekalan KKN akan dilaksanakan pada tanggal 15-16 Juni 2023</li>
                <li>Harap membawa berkas asli saat pembekalan</li>
            </ul>
        </div>
    </div>

    <div class="print-button text-center mt-4">
        <button onclick="window.print()" class="btn btn-warning">Cetak Bukti Pendaftaran</button>
    </div>
</div>

<div class="back-link mt-4">
    <a href="{{ route('dashboard') }}" class="btn">&larr; Kembali ke Dashboard</a>
</div>

<style>
    .completion-message {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .registration-summary {
        margin: 20px 0;
    }

    .summary-section {
        margin-bottom: 20px;
        background-color: #f9f9f9;
        padding: 15px;
        border-radius: 5px;
        border-left: 3px solid #005691;
    }

    .summary-section h4 {
        color: #005691;
        margin-top: 0;
        margin-bottom: 10px;
        font-size: 18px;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px dotted #ddd;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-item span {
        font-weight: bold;
        color: #555;
    }

    .next-info {
        margin: 20px 0;
    }

    .next-info h3 {
        color: #005691;
        margin-bottom: 10px;
        font-size: 18px;
    }

    .text-center {
        text-align: center;
    }

    .mt-4 {
        margin-top: 20px;
    }
</style>
@endsection

@section('scripts')
<script>
    // Script khusus untuk halaman ini (jika diperlukan)
</script>
@endsection

