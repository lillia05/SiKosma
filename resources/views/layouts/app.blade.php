<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SiKosma - Sistem Informasi Kos Mahasiswa')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        :root {
            --primary-blue: #1A4A7F;
            --primary-yellow: #FCD34D;
            --light-gray: #F3F4F6;
        }
        .bg-yellow-400 {
            background-color: #FCD34D !important;
        }
        .hover\:bg-yellow-500:hover {
            background-color: #FBBF24 !important;
        }
        .text-blue-900 {
            color: #1A4A7F !important;
        }
        .focus\:ring-blue-900:focus {
            --tw-ring-color: #1A4A7F !important;
        }
        .border-blue-900 {
            border-color: #1A4A7F !important;
        }
        .hover\:border-blue-900:hover {
            border-color: #1A4A7F !important;
        }
        .hover\:bg-blue-50:hover {
            background-color: #EFF6FF !important;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-gray);
        }
        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .logo-text {
            color: var(--primary-blue);
            font-weight: bold;
            font-size: 1.5rem;
        }
        .logo-subtitle {
            color: #666666;
            font-size: 0.7rem;
            font-weight: normal;
        }
        .btn-yellow {
            background-color: var(--primary-yellow);
            color: #000;
            border: none;
            padding: 8px 24px;
            border-radius: 8px;
            font-weight: 500;
        }
        .btn-yellow:hover {
            background-color: #FBBF24;
            color: #000;
        }
        .btn-blue {
            background-color: var(--primary-blue);
            color: white;
            border: none;
            padding: 8px 24px;
            border-radius: 8px;
            font-weight: 500;
        }
        .btn-blue:hover {
            background-color: #1E3A8A;
            color: white;
        }
        .search-bar {
            border: 2px solid var(--primary-blue);
            border-radius: 8px;
            padding: 12px 20px;
        }
        .filter-btn {
            background-color: var(--primary-yellow);
            color: #000;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            margin: 5px;
            font-weight: 500;
        }
        .filter-btn.active {
            background-color: var(--primary-yellow);
        }
        .filter-btn.inactive {
            background-color: white;
            border: 2px solid var(--primary-yellow);
            color: #000;
        }
        .kos-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.2s;
        }
        .kos-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .kos-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .kos-tag {
            background-color: var(--primary-yellow);
            color: #000;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            margin: 2px;
        }
        .kos-tag-white {
            background-color: white;
            border: 2px solid var(--primary-yellow);
            color: #000;
        }
        .star-rating {
            color: var(--primary-yellow);
        }
        html, body {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        body {
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
        .footer {
            background-color: white;
            padding: 20px 0;
            margin-top: auto;
            width: 100%;
        }
        .modal-content {
            border-radius: 12px;
            border: none;
        }
        .modal-header {
            border-bottom: 1px solid #e5e7eb;
        }
        .nav-link {
            color: var(--primary-blue) !important;
            font-weight: 500;
            text-decoration: none !important;
        }
        .nav-link:hover {
            color: var(--primary-blue) !important;
            text-decoration: underline !important;
        }
        .navbar-nav {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            list-style: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .navbar-nav .nav-item {
            display: block !important;
            margin: 0 !important;
        }
        .navbar-collapse {
            display: flex !important;
            flex-direction: row !important;
            justify-content: flex-end !important;
        }
        @media (max-width: 991px) {
            .navbar-collapse {
                flex-direction: column !important;
            }
            .navbar-nav {
                flex-direction: column !important;
                width: 100%;
            }
        }
        .btn-outline-secondary {
            border: 2px solid #e5e7eb;
            background-color: white;
        }
        .btn-outline-secondary:hover {
            background-color: #f9fafb;
            border-color: #d1d5db;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(30, 64, 175, 0.25);
        }
    </style>
    @yield('styles')
</head>
<body>
    @include('partials.navbar')
    
    <main>
        @yield('content')
    </main>
    
    @include('partials.footer')
    
    @include('partials.login-modal')
    
    @auth
        @include('partials.profile-modal')
    @endauth
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle filter buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Toggle active state
                if (this.classList.contains('inactive')) {
                    this.classList.remove('inactive');
                    this.classList.add('active');
                } else {
                    this.classList.remove('active');
                    this.classList.add('inactive');
                }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>

