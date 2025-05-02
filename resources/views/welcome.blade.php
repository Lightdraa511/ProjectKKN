<!DOCTYPE html>
<html lang="id">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Pendaftaran KKN Universitas Langlangbuana</title>
            <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f5f5f5;
        }
        header {
            background-color: #005691;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .logo img {
            height: 60px;
        }
        nav ul {
            display: flex;
            list-style: none;
            gap: 1.5rem;
        }
        nav a {
            color: white;
            text-decoration: none;
            font-weight: 500;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .hero {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset("images/hero-kkn.jpg") }}');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 4rem 2rem;
        }
        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        .btn {
            display: inline-block;
            background-color: #ffa500;
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1rem;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #e69500;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        .section-title {
            font-size: 1.8rem;
            color: #005691;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
        }
        .card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #005691;
            color: white;
            padding: 1rem;
            text-align: center;
        }
        .card-body {
            padding: 1.5rem;
        }
        .card-body ul {
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }
        .card-body p {
            margin-bottom: 1rem;
        }
        .timeline {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin: 3rem 0;
        }
        .timeline::before {
            content: '';
            position: absolute;
            top: 30px;
            left: 0;
            width: 100%;
            height: 4px;
            background-color: #005691;
            z-index: 1;
        }
        .timeline-item {
            position: relative;
            z-index: 2;
            text-align: center;
            width: 20%;
        }
        .timeline-dot {
            width: 30px;
            height: 30px;
            background-color: #005691;
            border-radius: 50%;
            margin: 0 auto 15px;
        }
        .timeline-content {
            background-color: white;
            padding: 1rem;
            border-radius: 5px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        .timeline-date {
            font-weight: bold;
            color: #ffa500;
            margin-bottom: 0.5rem;
        }
        .announcement {
            background-color: #f8f0d3;
            border-left: 5px solid #ffa500;
            padding: 1rem;
            margin-bottom: 2rem;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
        }
        .login-register {
            display: flex;
            gap: 1rem;
        }
        .login-btn {
            background-color: transparent;
            border: 2px solid white;
        }
        .login-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                padding: 1rem;
            }
            .logo {
                margin-bottom: 1rem;
            }
            nav ul {
                flex-wrap: wrap;
                justify-content: center;
            }
            .hero h1 {
                font-size: 2rem;
            }
            .timeline {
                flex-direction: column;
                margin: 2rem 0;
            }
            .timeline::before {
                height: 100%;
                width: 4px;
                top: 0;
                left: 30px;
            }
            .timeline-item {
                width: 100%;
                margin-bottom: 2rem;
                text-align: left;
                padding-left: 60px;
            }
            .timeline-dot {
                position: absolute;
                left: 15px;
                top: 0;
                margin: 0;
            }
        }
            </style>
    </head>
<body>
    <header>
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Universitas">
            <h1>Universitas Langlangbuana</h1>
        </div>
        <nav>
            <ul>
                <li><a href="#beranda">Beranda</a></li>
                <li><a href="#informasi">Informasi</a></li>
                <li><a href="#jadwal">Jadwal</a></li>
                <li><a href="#lokasi">Lokasi</a></li>
                <li><a href="#kontak">Kontak</a></li>
                <div class="login-register">
                    <a href="{{ route('login') }}" class="btn login-btn">Login</a>
                    <a href="{{ route('register') }}" class="btn">Daftar</a>
                </div>
            </ul>
                </nav>
        </header>

    <section class="hero" id="beranda">
        <h1>Pendaftaran Kuliah Kerja Nyata (KKN) {{ date('Y') }}</h1>
        <p>KKN merupakan program pengabdian kepada masyarakat yang memberikan pengalaman belajar dan bekerja bagi mahasiswa dalam membangun kehidupan masyarakat yang lebih baik.</p>
        <a href="{{ route('register') }}" class="btn">Daftar Sekarang</a>
    </section>

    <div class="container">
        <div class="announcement">
            <h3>Pengumuman Penting!</h3>
            <p>Pendaftaran KKN Periode I Tahun {{ date('Y') }} telah dibuka mulai tanggal
                {{ \App\Models\Setting::where('key', 'registration_start')->first() ? \Carbon\Carbon::parse(\App\Models\Setting::where('key', 'registration_start')->first()->value)->format('d F Y') : '1 Maret ' . date('Y') }}
                dan akan ditutup pada tanggal
                {{ \App\Models\Setting::where('key', 'registration_end')->first() ? \Carbon\Carbon::parse(\App\Models\Setting::where('key', 'registration_end')->first()->value)->format('d F Y') : '15 April ' . date('Y') }}.
                Pastikan untuk melengkapi semua persyaratan sebelum batas waktu pendaftaran.</p>
        </div>

        <section id="informasi">
            <h2 class="section-title">Informasi KKN</h2>
            <div class="cards">
                <div class="card">
                    <div class="card-header">
                        <h3>Persyaratan</h3>
                    </div>
                    <div class="card-body">
                        <ul>
                            <li>Mahasiswa aktif minimal semester 6</li>
                            <li>Telah menyelesaikan minimal
                                {{ \App\Models\Setting::where('key', 'min_sks')->first() ? \App\Models\Setting::where('key', 'min_sks')->first()->value : '100' }} SKS</li>
                            <li>IPK minimal
                                {{ \App\Models\Setting::where('key', 'min_gpa')->first() ? \App\Models\Setting::where('key', 'min_gpa')->first()->value : '2.75' }}</li>
                            <li>Telah menyelesaikan mata kuliah Metode Pengabdian Masyarakat</li>
                            <li>Bebas tanggungan administrasi</li>
                            <li>Sehat jasmani dan rohani</li>
                        </ul>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3>Dokumen Yang Diperlukan</h3>
                    </div>
                    <div class="card-body">
                        <ul>
                            <li>Fotokopi KTM (Kartu Tanda Mahasiswa)</li>
                            <li>Pas foto berwarna 4x6 (2 lembar)</li>
                            <li>Transkrip nilai terbaru</li>
                            <li>Bukti pembayaran biaya KKN</li>
                            <li>Surat izin orang tua/wali</li>
                            <li>Surat keterangan sehat dari dokter</li>
                    </ul>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3>Biaya dan Fasilitas</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Biaya KKN:</strong> Rp {{ number_format(\App\Models\Setting::where('key', 'registration_fee')->first() ? \App\Models\Setting::where('key', 'registration_fee')->first()->value : '500000', 0, ',', '.') }}</p>
                        <p>Sudah termasuk:</p>
                        <ul>
                            <li>Transportasi PP ke lokasi KKN</li>
                            <li>Akomodasi selama di lokasi</li>
                            <li>Seragam KKN (2 set)</li>
                            <li>Topi dan ID Card</li>
                            <li>Biaya operasional kegiatan</li>
                            <li>Pembekalan dan pelatihan</li>
                    </ul>
                    </div>
                </div>
            </div>
        </section>

        <section id="jadwal">
            <br>
            <h2 class="section-title">Jadwal Pelaksanaan</h2>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">
                            {{ \App\Models\Setting::where('key', 'registration_start')->first() ? \Carbon\Carbon::parse(\App\Models\Setting::where('key', 'registration_start')->first()->value)->format('d M') : '1 Mar' }} -
                            {{ \App\Models\Setting::where('key', 'registration_end')->first() ? \Carbon\Carbon::parse(\App\Models\Setting::where('key', 'registration_end')->first()->value)->format('d M Y') : '15 Apr ' . date('Y') }}
                        </div>
                        <div>Pendaftaran</div>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">1 - 5 Mei {{ date('Y') }}</div>
                        <div>Pembekalan</div>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">15 Mei {{ date('Y') }}</div>
                        <div>Pemberangkatan</div>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">
                            {{ \App\Models\Setting::where('key', 'kkn_start')->first() ? \Carbon\Carbon::parse(\App\Models\Setting::where('key', 'kkn_start')->first()->value)->format('d M') : '15 Mei' }} -
                            {{ \App\Models\Setting::where('key', 'kkn_end')->first() ? \Carbon\Carbon::parse(\App\Models\Setting::where('key', 'kkn_end')->first()->value)->format('d M Y') : '15 Jul ' . date('Y') }}
                        </div>
                        <div>Pelaksanaan KKN</div>
                    </div>
                </div>
            </div>
        </section>

        <section id="lokasi">
            <h2 class="section-title">Lokasi KKN</h2>
            <div class="cards">
                @php
                    $locations = \App\Models\Location::all()->groupBy(function($location) {
                        return explode(',', $location->name)[0] ?? 'Lainnya';
                    });
                @endphp

                @forelse($locations as $region => $locationGroup)
                <div class="card">
                    <div class="card-header">
                        <h3>{{ $region }}</h3>
                    </div>
                    <div class="card-body">
                        <ul>
                            @foreach($locationGroup as $location)
                            <li>{{ $location->name }} ({{ $location->total_quota }} kuota)</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @empty
                <div class="card">
                    <div class="card-header">
                        <h3>Lokasi KKN</h3>
                    </div>
                    <div class="card-body">
                        <p>Lokasi KKN akan segera diumumkan. Mohon tunggu informasi lebih lanjut.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </section>

        <section id="kontak">
            <br>
            <h2 class="section-title">Kontak</h2>
            <div class="card">
                <div class="card-body">
                    <p><strong>P3MM (Biro Pengembangan Prestasi dan Pengabdian Kepada Masyarakat oleh Mahasiswa di Universitas Langlangbuana)</strong></p>
                    <p>Gedung Rektorat Lantai 3, Universitas Langlangbuana</p>
                    <p>Jl. Lengkong</p>
                    <p>Telepon: 081264768789</p>
                    <p>Email: kkn@unla.ac.id</p>
                    <p>Jam Operasional: Senin - Jumat, 08.00 - 16.00 WIB</p>
                </div>
            </div>
        </section>
        </div>

    <footer>
        <p>&copy; {{ date('Y') }} P3MM (Biro Pengembangan Prestasi dan Pengabdian Kepada Masyarakat oleh Mahasiswa di Universitas Langlangbuana)</p>
        <p>Jl. Lengkong</p>
    </footer>
    </body>
</html>
