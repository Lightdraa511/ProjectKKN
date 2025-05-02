@extends('layouts.user')

@section('title', 'Pilih Lokasi KKN - Sistem Pendaftaran KKN')

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

<h2>Pilih Lokasi KKN</h2>

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

@if(session('info'))
<div class="alert alert-info">
    {{ session('info') }}
</div>
@endif

<div class="form-group">
    <div class="alert alert-info">
        Silakan pilih lokasi KKN yang Anda inginkan. Kuota yang ditampilkan adalah kuota khusus untuk fakultas Anda.
    </div>
</div>

@if($locations->isEmpty())
<div class="alert alert-warning">
    Maaf, tidak ada lokasi KKN yang tersedia untuk fakultas Anda saat ini. Silakan hubungi administrator untuk informasi lebih lanjut.
</div>
@else
<div class="locations-grid">
    @foreach($locations as $location)
    <div class="location-card">
        <div class="location-name">{{ $location->name }}</div>
        <div class="location-description">{{ $location->description }}</div>

        <div class="quota-details">
            <h4>Kuota per Fakultas:</h4>
            <div class="faculty-quotas-table">
                <table class="quota-table">
                    <thead>
                        <tr>
                            <th>Fakultas</th>
                            <th>Terisi</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($location->facultyQuotas as $facultyQuota)
                        <tr @if($facultyQuota->faculty_id == auth()->user()->faculty_id) class="highlight-row" @endif>
                            <td>{{ $facultyQuota->faculty->name }}</td>
                            <td>{{ $facultyQuota->getAssignedCount() }}</td>
                            <td>{{ $facultyQuota->quota }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="quota-summary">
            @php
                $facultyQuota = $location->facultyQuotas->where('faculty_id', auth()->user()->faculty_id)->first();
                $availableQuota = $facultyQuota ? $facultyQuota->quota - $facultyQuota->getAssignedCount() : 0;
                $totalAssigned = $location->getAssignedCount();
                $totalQuota = $location->total_quota;
            @endphp

            <div class="faculty-quota">
                <span><strong>Kuota {{ auth()->user()->faculty->name }}:</strong></span>
                <span>{{ $facultyQuota ? $facultyQuota->getAssignedCount() : 0 }}/{{ $facultyQuota ? $facultyQuota->quota : 0 }}</span>
            </div>

            <div class="total-quota">
                <span><strong>Total Kuota:</strong></span>
                <span>{{ $totalAssigned }}/{{ $totalQuota }}</span>
            </div>
        </div>

        <div class="button-container">
            @if($availableQuota > 0)
            <a href="{{ route('locations.select', $location) }}" class="btn btn-primary">Pilih Lokasi Ini</a>
            @else
            <button class="btn btn-disabled" disabled>Kuota Penuh</button>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endif

<div class="back-link mt-4">
    <a href="{{ route('dashboard') }}" class="btn">&larr; Kembali ke Dashboard</a>
</div>

<style>
    .locations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .location-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .location-name {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .location-description {
        color: #666;
        margin-bottom: 15px;
    }

    .quota-details {
        margin-top: 15px;
        margin-bottom: 15px;
    }

    .quota-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        font-size: 14px;
    }

    .quota-table th, .quota-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    .quota-table th {
        background-color: #f5f5f5;
    }

    .highlight-row {
        background-color: #fff9e6;
        font-weight: bold;
    }

    .quota-summary {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
        padding: 10px;
        background-color: #f9f9f9;
        border-radius: 5px;
    }

    .faculty-quota, .total-quota {
        display: flex;
        flex-direction: column;
    }

    .button-container {
        margin-top: 15px;
        text-align: center;
    }

    .btn-disabled {
        background-color: #cccccc;
        color: #666666;
        cursor: not-allowed;
    }
</style>
@endsection
