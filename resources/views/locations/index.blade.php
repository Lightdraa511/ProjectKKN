@extends('layouts.app')

@section('title', 'Pilih Lokasi KKN - Sistem Pendaftaran KKN')

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

            @php
                $facultyQuota = $location->facultyQuotas->where('faculty_id', auth()->user()->faculty_id)->first();
                $availableQuota = $facultyQuota ? $facultyQuota->quota - $facultyQuota->assigned_count : 0;
            @endphp

            <div class="quota-info">
                <div class="faculty-quota">
                    <span>Kuota Tersedia</span>
                    <span>{{ $availableQuota }}/{{ $facultyQuota ? $facultyQuota->quota : 0 }}</span>
                </div>
            </div>

            <a href="{{ route('locations.select', $location) }}" class="btn btn-primary">Pilih Lokasi Ini</a>
        </div>
        @endforeach
    </div>
    @endif

    <div class="back-link">
        <a href="{{ route('dashboard') }}">&larr; Kembali ke Dashboard</a>
    </div>
</div>
@endsection
