<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Sistem Pendaftaran KKN')</title>
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
            height: 40px;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .logout-btn {
            background-color: #ffa500;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
        }
        .logout-btn:hover {
            background-color: #e69500;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        .main-content {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }
        .steps {
            display: flex;
            margin-bottom: 30px;
            overflow-x: auto;
        }
        .step {
            flex: 1;
            text-align: center;
            padding: 10px;
            position: relative;
            min-width: 100px;
        }
        .step::after {
            content: '';
            position: absolute;
            top: 20px;
            right: -10px;
            height: 2px;
            width: 20px;
            background-color: #ccc;
            z-index: 0;
        }
        .step:last-child::after {
            display: none;
        }
        .step-number {
            width: 30px;
            height: 30px;
            background-color: #ccc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            color: white;
            position: relative;
            z-index: 1;
        }
        .step.active .step-number {
            background-color: #005691;
        }
        .step.completed .step-number {
            background-color: #4CAF50;
        }
        .step-title {
            font-size: 12px;
            color: #555;
        }
        .step.active .step-title {
            color: #005691;
            font-weight: bold;
        }
        h2 {
            color: #005691;
            margin-bottom: 1.5rem;
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .alert-info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }
        .alert-warning {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
        }
        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #005691;
        }
        .stat-card h3 {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
        }
        .stat-card .number {
            font-size: 28px;
            font-weight: bold;
            color: #005691;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 12px;
            border-radius: 10px;
            margin-left: 5px;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .tab-container {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background-color: white;
        }
        .tab-header {
            display: flex;
            background-color: #f8f9fa;
            border-bottom: 1px solid #ddd;
        }
        .tab {
            padding: 12px 20px;
            cursor: pointer;
            font-weight: 500;
            color: #555;
            border-bottom: 2px solid transparent;
        }
        .tab.active {
            color: #005691;
            border-bottom: 2px solid #005691;
            background-color: white;
        }
        .tab-content {
            padding: 20px;
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .btn {
            display: inline-block;
            background-color: #005691;
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #004677;
        }
        .btn-warning {
            background-color: #ffa500;
        }
        .btn-warning:hover {
            background-color: #e69500;
        }
        .timeline {
            margin: 2rem 0;
            position: relative;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #005691;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 30px;
            padding-left: 60px;
        }
        .timeline-icon {
            position: absolute;
            left: 10px;
            width: 20px;
            height: 20px;
            background: #005691;
            border-radius: 50%;
            text-align: center;
            line-height: 20px;
            color: white;
            font-size: 12px;
        }
        .timeline-content {
            background: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .timeline-date {
            font-weight: bold;
            color: #ffa500;
            margin-bottom: 10px;
        }
        .locations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .location-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .location-name {
            background-color: #005691;
            color: white;
            padding: 10px 15px;
            font-size: 16px;
            font-weight: bold;
        }
        .location-description {
            padding: 15px;
            font-size: 14px;
            color: #666;
            border-bottom: 1px solid #eee;
        }
        .quota-info {
            padding: 15px;
        }
        .faculty-quota {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 14px;
        }
        .select-btn {
            width: 100%;
            padding: 10px;
            background-color: #ffa500;
            color: white;
            border: none;
            font-weight: bold;
            cursor: pointer;
        }
        .select-btn:hover {
            background-color: #e69500;
        }
        .select-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
        }
        @yield('additional-styles')
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="https://placehold.co/40x40/005691/FFF?text=UNLA" alt="Logo Universitas">
            <h2>Sistem Pendaftaran KKN</h2>
        </div>
        <div class="user-info">
            <span>{{ auth()->user()->name }} ({{ auth()->user()->faculty->name }})</span>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </header>

    <div class="container">
        <div class="main-content">
            @yield('content')
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Universitas Langlangbuana - Sistem Pendaftaran KKN</p>
    </footer>

    @yield('scripts')
</body>
</html>
