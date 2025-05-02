@extends('layouts.user')

@section('title', 'Konfirmasi Lokasi KKN - Sistem Pendaftaran KKN')

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
        @if(isset($location->address))
        <div class="info-item">
            <strong>Alamat:</strong>
            <span>{{ $location->address }}</span>
        </div>
        @endif

        @php
            $totalAssigned = $location->getAssignedCount();
            $totalQuota = $location->total_quota;
        @endphp

        <div class="info-item">
            <strong>Total Kuota Terisi:</strong>
            <span>{{ $totalAssigned }}/{{ $totalQuota }}</span>
        </div>
    </div>

    <div class="quota-details">
        <h4>Rincian Kuota per Fakultas:</h4>
        <table class="quota-table">
            <thead>
                <tr>
                    <th>Fakultas</th>
                    <th>Terisi</th>
                    <th>Total</th>
                    <th>Sisa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($location->facultyQuotas as $quota)
                @php
                    $assigned = $quota->getAssignedCount();
                    $available = $quota->quota - $assigned;
                @endphp
                <tr @if($quota->faculty_id == auth()->user()->faculty_id) class="highlight-row" @endif>
                    <td>{{ $quota->faculty->name }}</td>
                    <td>{{ $assigned }}</td>
                    <td>{{ $quota->quota }}</td>
                    <td>{{ $available }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="user-faculty-quota">
        <h4>Kuota Fakultas Anda ({{ auth()->user()->faculty->name }}):</h4>
        @php
            $availableQuota = $facultyQuota->quota - $facultyQuota->getAssignedCount();
        @endphp
        <div class="quota-box">
            <div class="quota-item">
                <span>Terisi:</span>
                <strong>{{ $facultyQuota->getAssignedCount() }}</strong>
            </div>
            <div class="quota-item">
                <span>Total:</span>
                <strong>{{ $facultyQuota->quota }}</strong>
            </div>
            <div class="quota-item">
                <span>Sisa:</span>
                <strong>{{ $availableQuota }}</strong>
            </div>
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

        <a href="{{ route('locations.index') }}" class="btn btn-secondary mt-3">Kembali ke Daftar Lokasi</a>
    </div>
</div>

<style>
    .location-details {
        background-color: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .location-info {
        margin: 20px 0;
        background-color: #f9f9f9;
        padding: 15px;
        border-radius: 5px;
    }

    .info-item {
        margin-bottom: 10px;
    }

    .quota-details {
        margin: 20px 0;
    }

    .quota-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .quota-table th, .quota-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }

    .quota-table th {
        background-color: #f5f5f5;
    }

    .highlight-row {
        background-color: #fff9e6;
        font-weight: bold;
    }

    .user-faculty-quota {
        margin: 20px 0;
        padding: 15px;
        background-color: #f0f8ff;
        border-radius: 5px;
        border-left: 4px solid #3490dc;
    }

    .quota-box {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
    }

    .quota-item {
        text-align: center;
        padding: 10px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        flex: 1;
        margin: 0 5px;
    }

    .quota-item strong {
        display: block;
        font-size: 24px;
        color: #3490dc;
        margin-top: 5px;
    }

    .confirmation-alert {
        margin: 20px 0;
    }

    .action-buttons {
        text-align: center;
        margin-top: 20px;
    }

    .mt-3 {
        margin-top: 15px;
    }

    .btn-secondary {
        background-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }
</style>
@endsection
