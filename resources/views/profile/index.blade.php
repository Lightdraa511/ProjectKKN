@extends('layouts.app')

@section('title', 'Lengkapi Data Diri - Sistem Pendaftaran KKN')

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
        <div class="step completed" id="step3">
            <div class="step-number">3</div>
            <div class="step-title">Pilih Lokasi</div>
        </div>
        <div class="step active" id="step4">
            <div class="step-number">4</div>
            <div class="step-title">Data Diri</div>
        </div>
        <div class="step" id="step5">
            <div class="step-number">5</div>
            <div class="step-title">Selesai</div>
        </div>
    </div>

    <h2>Lengkapi Data Diri</h2>

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

    <div class="form-group">
        <div class="alert alert-info">
            Silakan lengkapi data diri Anda sesuai dengan formulir di bawah ini. Data ini akan digunakan untuk keperluan KKN.
        </div>
    </div>

    <form method="POST" action="{{ route('profile.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="section-title">
            <h3>Informasi Pribadi</h3>
        </div>

        <div class="form-group">
            <label for="address">Alamat Lengkap</label>
            <textarea id="address" name="address" rows="3" required>{{ old('address', $profile->address ?? '') }}</textarea>
            @error('address')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone">Nomor Telepon</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone', $profile->phone ?? '') }}" required>
            @error('phone')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="section-title">
            <h3>Kontak Darurat</h3>
        </div>

        <div class="form-group">
            <label for="emergency_contact">Kontak Darurat</label>
            <input type="text" id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact', $profile->emergency_contact ?? '') }}" required>
            <small>Format: Nama - Hubungan - Nomor Telepon (contoh: Budi - Orang Tua - 081234567890)</small>
            @error('emergency_contact')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="section-title">
            <h3>Informasi Kesehatan</h3>
        </div>

        <div class="form-group">
            <label for="blood_type">Golongan Darah</label>
            <select id="blood_type" name="blood_type" required>
                <option value="">-- Pilih Golongan Darah --</option>
                @foreach(['A', 'B', 'AB', 'O'] as $type)
                    <option value="{{ $type }}" {{ old('blood_type', $profile->blood_type ?? '') == $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
            @error('blood_type')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="disease_history">Riwayat Penyakit</label>
            <textarea id="disease_history" name="disease_history" rows="3">{{ old('disease_history', $profile->disease_history ?? '') }}</textarea>
            <small>Opsional. Tulis riwayat penyakit jika ada.</small>
            @error('disease_history')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="section-title">
            <h3>Keahlian dan Keterampilan</h3>
        </div>

        <div class="form-group">
            <label for="skills">Keahlian dan Keterampilan</label>
            <textarea id="skills" name="skills" rows="3" required>{{ old('skills', $profile->skills ?? '') }}</textarea>
            <small>Tuliskan keahlian dan keterampilan yang Anda miliki yang dapat dimanfaatkan selama KKN.</small>
            @error('skills')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">Simpan Data Diri</button>
    </form>

    <div class="back-link">
        <a href="{{ route('dashboard') }}">&larr; Kembali ke Dashboard</a>
    </div>
</div>
@endsection
