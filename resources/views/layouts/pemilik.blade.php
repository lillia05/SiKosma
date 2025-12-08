<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Pemilik - SiKosma')</title>
    
    <!-- Vite CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @yield('styles')
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('pemilik.dashboard') }}" class="flex items-center gap-2 no-underline">
                    @php
                        $logoUrl = \App\Helpers\LogoHelper::getLogoUrl();
                    @endphp
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="SiKosma Logo" class="h-10 w-auto" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    @endif
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="{{ $logoUrl ? 'hidden' : '' }}">
                        <path d="M20 5L35 15V30L20 40L5 30V15L20 5Z" fill="#1A4A7F"/>
                        <path d="M20 10L30 17.5V27.5L20 35L10 27.5V17.5L20 10Z" fill="#FCD34D"/>
                    </svg>
                </a>
                
                <div class="flex items-center gap-6">
                    <a href="{{ route('pemilik.dashboard') }}" class="font-poppins transition text-base no-underline {{ request()->routeIs('pemilik.dashboard') ? 'text-primary-blue font-semibold' : 'text-gray-600 font-medium' }} hover:underline">
                        Beranda
                    </a>
                    <a href="{{ route('pemilik.properti') }}" class="font-poppins transition text-base no-underline {{ request()->routeIs('pemilik.properti') ? 'text-primary-blue font-semibold' : 'text-gray-600 font-medium' }} hover:underline">
                        Properti Kos Saya
                    </a>
                    <a href="{{ route('pemilik.pemesanan') }}" class="font-poppins transition text-base no-underline {{ request()->routeIs('pemilik.pemesanan') ? 'text-primary-blue font-semibold' : 'text-gray-600 font-medium' }} hover:underline">
                        Pemesanan
                    </a>
                    <a href="{{ route('pemilik.laporan') }}" class="font-poppins transition text-base no-underline {{ request()->routeIs('pemilik.laporan') ? 'text-primary-blue font-semibold' : 'text-gray-600 font-medium' }} hover:underline">
                            Laporan
                        </a>
                    
                    <!-- Profile Icon -->
                    <div class="relative" id="pemilikProfileDropdownContainer">
                        <button type="button" id="pemilikProfileDropdownButton" class="flex items-center gap-2 text-primary-blue font-medium text-base cursor-pointer focus:outline-none">
                            @if(Auth::user()->profile_photo_url)
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile" class="rounded-full w-10 h-10 object-cover">
                            @else
                                <div class="rounded-full w-10 h-10 flex items-center justify-center text-white font-bold text-sm bg-gradient-to-br from-primary-blue to-primary-yellow">
                                    {{ mb_substr(Auth::user()->nama, 0, 1) }}
                                </div>
                            @endif
                        </button>
                        <div id="pemilikProfileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200">
                            <a href="{{ route('pemilik.dashboard', ['modal' => 'profile']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 no-underline">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    <span>Profil</span>
                                </div>
                            </a>
                            <hr class="my-1 border-gray-200">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                                        </svg>
                                        <span>Keluar</span>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Content -->
    <main class="flex-1">
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-6 mt-12">
        <div class="container mx-auto px-6 text-center">
            <div class="flex items-center justify-center gap-2 mb-2">
                @php
                    $footerLogoUrl = \App\Helpers\LogoHelper::getLogoUrl();
                @endphp
                @if($footerLogoUrl)
                    <img src="{{ $footerLogoUrl }}" alt="SiKosma Logo" class="h-8 w-auto" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                @endif
                <svg width="32" height="32" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="{{ $footerLogoUrl ? 'hidden' : '' }}">
                    <path d="M20 5L35 15V30L20 40L5 30V15L20 5Z" fill="#1A4A7F"/>
                    <path d="M20 10L30 17.5V27.5L20 35L10 27.5V17.5L20 10Z" fill="#FCD34D"/>
                </svg>
            </div>
            <p class="text-sm text-gray-600 font-poppins">Copyright 2025 SiKosma. All rights reserved</p>
        </div>
    </footer>
    
    @include('partials.profile-modal')
    
    <script>
        // Profile Dropdown Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const profileDropdownButton = document.getElementById('pemilikProfileDropdownButton');
            const profileDropdown = document.getElementById('pemilikProfileDropdown');
            const profileDropdownContainer = document.getElementById('pemilikProfileDropdownContainer');
            const profileModal = document.getElementById('profileModalOverlay');
            
            if (profileDropdownButton && profileDropdown) {
                profileDropdownButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    profileDropdown.classList.toggle('hidden');
                });
                
                document.addEventListener('click', function(event) {
                    if (profileDropdownContainer && !profileDropdownContainer.contains(event.target)) {
                        profileDropdown.classList.add('hidden');
                    }
                });
                
                profileDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            // Handle profile modal close - stay on current page
            if (profileModal) {
                // Close on overlay click
                profileModal.addEventListener('click', function(e) {
                    if (e.target === profileModal) {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('modal');
                        url.searchParams.delete('edit');
                        window.location.href = url.toString();
                    }
                });

                // Close button in modal (X button) - prevent default link behavior
                const closeButton = profileModal.querySelector('a[href*="pemilik.dashboard"]');
                if (closeButton) {
                    closeButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = new URL(window.location.href);
                        url.searchParams.delete('modal');
                        url.searchParams.delete('edit');
                        window.location.href = url.toString();
                    });
                }
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>

