<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SiKosma - Sistem Informasi Kos Mahasiswa')</title>
    
    <!-- Vite CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @yield('styles')
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    @include('partials.navbar')
    
    <main class="flex-1">
        @yield('content')
    </main>
    
    @include('partials.footer')
    
    @include('partials.login-modal')
    @include('partials.register-modal')
    
    @auth
        @include('partials.profile-modal')
    @endauth
    
    @yield('scripts')
    
    <script>
        // Profile Dropdown Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const profileDropdownButton = document.getElementById('profileDropdownButton');
            const profileDropdown = document.getElementById('profileDropdown');
            const profileDropdownContainer = document.getElementById('profileDropdownContainer');
            
            if (profileDropdownButton && profileDropdown) {
                // Toggle dropdown on button click
                profileDropdownButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    profileDropdown.classList.toggle('hidden');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (profileDropdownContainer && !profileDropdownContainer.contains(event.target)) {
                        profileDropdown.classList.add('hidden');
                    }
                });
                
                // Prevent dropdown from closing when clicking inside
                profileDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });
    </script>
</body>
</html>

