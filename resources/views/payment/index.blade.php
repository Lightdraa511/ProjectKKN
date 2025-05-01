@extends('layouts.app')

@section('title', 'Pembayaran KKN - Sistem Pendaftaran KKN')

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
        <div class="step active" id="step2">
            <div class="step-number">2</div>
            <div class="step-title">Pembayaran</div>
        </div>
        <div class="step" id="step3">
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

    <h2>Pembayaran Pendaftaran KKN</h2>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="payment-info">
        <div class="alert alert-info">
            <strong>Petunjuk Pembayaran:</strong>
            <p>Silakan transfer biaya pendaftaran KKN sebesar Rp 500.000 ke rekening berikut:</p>
            <ul>
                <li>Bank BNI</li>
                <li>Nomor Rekening: 0123456789</li>
                <li>Atas Nama: Universitas Indonesia</li>
            </ul>
            <p>Gunakan kode pembayaran <strong>KKN-{{ auth()->id() }}</strong> sebagai berita transfer.</p>
        </div>

        <div class="payment-details">
            <h3>Detail Pembayaran</h3>
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
                <strong>Menunggu Pembayaran</strong>
            </div>
        </div>

        <!-- Untuk demo, kita gunakan form sederhana -->
        <form method="POST" action="{{ route('payment.process') }}">
            @csrf
            <h3>Konfirmasi Pembayaran</h3>

            <div class="alert alert-warning">
                <p><strong>Catatan:</strong> Ini adalah halaman simulasi pendaftaran. Untuk demo, Anda bisa langsung melanjutkan tanpa perlu mengisi detail pembayaran.</p>
            </div>

            <div class="form-group">
                <input type="checkbox" id="confirm" name="confirm" value="1" required>
                <label for="confirm">Saya konfirmasi telah melakukan pembayaran sebesar Rp 500.000 ke rekening yang ditentukan.</label>
                @error('confirm')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit">Konfirmasi Pembayaran</button>
        </form>
    </div>

    <div class="back-link">
        <a href="{{ route('dashboard') }}">&larr; Kembali ke Dashboard</a>
    </div>
</div>
@endsection
