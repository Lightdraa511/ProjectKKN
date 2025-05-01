@extends('layouts.app')

@section('title', 'Konfirmasi Lokasi KKN - Sistem Pendaftaran KKN')

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

    <h2>Konfirmasi Lokasi KKN</h2>

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="location-details">
        <h3>{{ $location->name }}</h3>
        <p>{{ $location->description }}</p>

        <div class="location-info">
            <div class="info-item">
                <strong>Alamat:</strong>
                <span>{{ $location->address }}</span>
            </div>

            <div class="info-item">
                <strong>Kuota untuk {{ auth()->user()->faculty->name }}:</strong>
                <span>{{ $facultyQuota->assigned_count }}/{{ $facultyQuota->quota }}</span>
            </div>

            @php
                $availableQuota = $facultyQuota->quota - $facultyQuota->assigned_count;
            @endphp

            <div class="info-item">
                <strong>Sisa Kuota:</strong>
                <span>{{ $availableQuota }}</span>
            </div>
        </div>

        <div class="confirmation-alert alert alert-warning">
            <strong>Perhatian!</strong> Setelah memilih lokasi KKN, Anda masih dapat membatalkannya selama belum menyelesaikan proses pendaftaran. Namun setelah pendaftaran selesai, pilihan lokasi tidak dapat diubah.
        </div>

        <div class="action-buttons">
            <form method="POST" action="{{ route('locations.confirm', $location) }}">
                @csrf
                <button type="submit" class="btn btn-primary">Konfirmasi Pilihan</button>
            </form>

            <a href="{{ route('locations.index') }}" class="btn btn-secondary">Kembali ke Daftar Lokasi</a>
        </div>
    </div>
</div>
@endsection
