<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Sistem Pendaftaran KKN')</title>
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
            width: 90%;
            max-width: 800px;
            margin: 50px auto;
        }
        h1, h2, h3 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input[type="text"], input[type="password"], input[type="email"], input[type="tel"], select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button, .btn {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 10px;
            text-align: center;
            display: inline-block;
            text-decoration: none;
        }
        button:hover, .btn:hover {
            background-color: #45a049;
        }
        button:disabled, .btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
        .links {
            text-align: center;
            margin-top: 20px;
        }
        a {
            color: #4CAF50;
            text-decoration: none;
            cursor: pointer;
        }
        a:hover {
            text-decoration: underline;
        }
        .navbar {
            background-color: #333;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h2 {
            margin: 0;
            color: white;
        }
        .user-info {
            display: flex;
            align-items: center;
        }
        .user-info span {
            margin-right: 15px;
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
        .tab-container {
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .tab-header {
            display: flex;
            background-color: #f1f1f1;
        }
        .tab {
            padding: 10px 15px;
            cursor: pointer;
            flex-grow: 1;
            text-align: center;
        }
        .tab.active {
            background-color: #fff;
            border-bottom: 2px solid #4CAF50;
        }
        .tab-content {
            padding: 15px;
            display: none;
        }
        .tab-content.active {
            display: block;
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
            background-color: #4CAF50;
        }
        .step.completed .step-number {
            background-color: #45a049;
        }
        .step-title {
            font-size: 12px;
            color: #555;
        }
        .step.active .step-title {
            color: #4CAF50;
            font-weight: bold;
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
        .locations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .location-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            background-color: white;
        }
        .location-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .location-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        .quota-info {
            margin-bottom: 10px;
        }
        .faculty-quota {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .select-btn {
            width: 100%;
        }

        @yield('additional_styles')
    </style>
</head>
<body>
    @yield('navbar')

    <div class="content">
        @yield('content')
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
