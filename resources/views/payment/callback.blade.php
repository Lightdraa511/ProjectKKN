@extends('layouts.app')

@section('title', 'Pembayaran Berhasil - Sistem Pendaftaran KKN')

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
        <div class="step completed" id="step1">
            <div class="step-number">1</div>
            <div class="step-title">Dashboard</div>
        </div>
        <div class="step completed" id="step2">
            <div class="step-number">2</div>
            <div class="step-title">Pembayaran</div>
        </div>
        <div class="step active" id="step3">
            <div class="step-number">3</div>
            <div class="step-title">Pilih Lokasi</div>
        </div>
        <div class="step" id="step4">
            <div class="step-number">4</div>
            <div class="step-title">Data Diri</div>
        </div>
        <div class="step" id="step5">
            <div class="step-number">5</div>
            <div class="step-title">Selesai</div>
        </div>
    </div>

    <h2>Pembayaran Berhasil</h2>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="payment-success">
        <div class="alert alert-success">
            <i class="icon-check"></i>
            <h3>Pembayaran Anda Berhasil Diverifikasi</h3>
            <p>Terima kasih telah melakukan pembayaran pendaftaran KKN. Pembayaran Anda telah berhasil diverifikasi.</p>
        </div>

        <div class="payment-details">
            <h3>Rincian Pembayaran</h3>
            <div class="detail-item">
                <span>Kode Pembayaran:</span>
                <strong>KKN-{{ auth()->id() }}</strong>
            </div>
            <div class="detail-item">
                <span>Jumlah:</span>
                <strong>Rp 500.000</strong>
            </div>
            <div class="detail-item">
                <span>Status:</span>
                <strong>Pembayaran Diterima</strong>
            </div>
            <div class="detail-item">
                <span>Waktu Verifikasi:</span>
                <strong>{{ now()->format('d M Y H:i') }}</strong>
            </div>
        </div>

        <div class="next-steps">
            <h3>Langkah Selanjutnya</h3>
            <p>Silakan pilih lokasi KKN yang Anda inginkan dengan klik tombol di bawah ini.</p>
            <a href="{{ route('locations.index') }}" class="btn btn-primary">Pilih Lokasi KKN</a>
        </div>
    </div>

    <div class="back-link">
        <a href="{{ route('dashboard') }}">&larr; Kembali ke Dashboard</a>
    </div>
</div>
@endsection
