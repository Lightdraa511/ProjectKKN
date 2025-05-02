@extends('layouts.user')

@section('title', 'Menunggu Pembayaran - Sistem Pendaftaran KKN')

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

<h2>Menunggu Konfirmasi Pembayaran</h2>

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

<div class="payment-waiting">
    <div class="alert alert-info">
        <h3>Pembayaran Sedang Diproses</h3>
        <p>Kami sedang menunggu konfirmasi pembayaran dari penyedia jasa pembayaran. Proses ini mungkin membutuhkan waktu beberapa saat.</p>
    </div>

    <div class="payment-details">
        <h3>Rincian Pembayaran</h3>
        <div class="detail-item">
            <span>Kode Pembayaran:</span>
            <strong>{{ $payment->transaction_id }}</strong>
        </div>
        <div class="detail-item">
            <span>Jumlah:</span>
            <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong>
        </div>
        <div class="detail-item">
            <span>Status:</span>
            <strong id="payment-status">Menunggu Konfirmasi</strong>
        </div>
        <div class="detail-item">
            <span>Waktu Pembayaran:</span>
            <strong>{{ $payment->created_at->format('d M Y H:i') }}</strong>
        </div>
    </div>

    <div class="text-center mt-4">
        <p>Halaman ini akan menyegarkan otomatis dalam <span id="countdown">15</span> detik...</p>
        <button id="check-status-btn" class="btn btn-warning mt-3">Periksa Status Sekarang</button>
        <a href="{{ route('dashboard') }}" class="btn mt-3">Kembali ke Dashboard</a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var countdown = 15;
        var countdownElement = document.getElementById('countdown');
        var paymentStatus = document.getElementById('payment-status');
        var checkStatusBtn = document.getElementById('check-status-btn');
        var orderId = "{{ $payment->transaction_id }}";

        function checkPaymentStatus() {
            fetch('/payment/check-status?order_id=' + orderId, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    paymentStatus.textContent = 'Berhasil';
                    paymentStatus.classList.add('text-success');

                    // Redirect ke halaman lokasi setelah 2 detik
                    setTimeout(function() {
                        window.location.href = "{{ route('locations.index') }}";
                    }, 2000);
                } else if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    // Lanjutkan countdown
                    countdown = 15;
                }
            })
            .catch(error => {
                console.error('Error checking payment status:', error);
            });
        }

        // Periksa status setiap interval waktu
        var countdownTimer = setInterval(function() {
            countdown--;
            countdownElement.textContent = countdown;

            if (countdown <= 0) {
                // Reset countdown
                countdown = 15;
                // Periksa status pembayaran
                checkPaymentStatus();
            }
        }, 1000);

        // Tombol untuk memeriksa status secara manual
        checkStatusBtn.addEventListener('click', function() {
            checkPaymentStatus();
            // Reset countdown
            countdown = 15;
            countdownElement.textContent = countdown;
        });
    });
</script>
@endsection
