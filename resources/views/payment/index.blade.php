@extends('layouts.user')

@section('title', 'Pembayaran KKN - Sistem Pendaftaran KKN')

@section('content')
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
        <strong>Informasi Pembayaran:</strong>
        <p>Untuk melanjutkan pendaftaran KKN, Anda diharuskan membayar biaya pendaftaran sebesar Rp {{ number_format($amount, 0, ',', '.') }}.</p>
        <p>Silakan pilih metode pembayaran yang tersedia dengan mengklik tombol "Bayar Sekarang" di bawah ini.</p>
    </div>

    <div class="payment-details">
        <h3>Detail Pembayaran</h3>
        <div class="detail-item">
            <span>Kode Pembayaran:</span>
            <strong>{{ $order_id }}</strong>
        </div>
        <div class="detail-item">
            <span>Jumlah:</span>
            <strong>Rp {{ number_format($amount, 0, ',', '.') }}</strong>
        </div>
        <div class="detail-item">
            <span>Status:</span>
            <strong>Menunggu Pembayaran</strong>
        </div>
    </div>

    <div class="text-center mt-4">
        <button id="pay-button" class="btn btn-warning">Bayar Sekarang</button>
    </div>
</div>

<div class="back-link mt-4">
    <a href="{{ route('dashboard') }}" class="btn">&larr; Kembali ke Dashboard</a>
</div>
@endsection

@section('scripts')
<!-- Include Midtrans SNAP -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const payButton = document.getElementById('pay-button');

        payButton.addEventListener('click', function() {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    window.location.href = '{{ route('payment.finish') }}?order_id={{ $order_id }}';
                },
                onPending: function(result) {
                    window.location.href = '{{ route('payment.finish') }}?order_id={{ $order_id }}';
                },
                onError: function(result) {
                    window.location.href = '{{ route('payment.finish') }}?order_id={{ $order_id }}';
                },
                onClose: function() {
                    // Callback ketika customer menutup popup tanpa menyelesaikan pembayaran
                    alert('Anda menutup pembayaran tanpa menyelesaikannya. Silakan coba lagi.');
                }
            });
        });
    });
</script>
@endsection
