<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - SiKosma')</title>
    
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
<body class="bg-gray-100" style="margin: 0; padding: 0; overflow-x: hidden;">
    <div class="min-h-screen flex">
        <!-- Sidebar Fixed -->
        <aside class="fixed inset-y-0 left-0 z-50 w-[220px] bg-[#0A3B65] text-white">
            @include('partials.admin-sidebar')
        </aside>

        <!-- Main Content dengan margin-left untuk sidebar - Layout ini bekerja karena menggunakan flexbox -->
        <div class="flex-1 ml-[220px] flex flex-col min-h-screen">
            
            <!-- Header - Transparent -->
            <header class="sticky top-0 z-40 px-8 py-4 flex justify-end items-center">
                <div class="relative" id="adminProfileDropdownContainer">
                    <button type="button" id="adminProfileDropdownButton" class="flex items-center gap-3 text-gray-900 font-medium text-base cursor-pointer focus:outline-none hover:opacity-80 transition">
                        <div class="text-right">
                            <p class="text-lg font-bold text-gray-900 font-poppins leading-tight mb-0.5">{{ Auth::user()->nama }}</p>
                            <p class="text-xs text-gray-600 font-poppins">Admin</p>
                        </div>
                        <div class="w-12 h-12 rounded-full overflow-hidden bg-gradient-to-br from-blue-900 to-yellow-400 flex items-center justify-center text-white text-lg font-bold shadow-md flex-shrink-0">
                            @php
                                $user = Auth::user();
                                $hasPhoto = isset($user->foto_profil) && $user->foto_profil;
                            @endphp
                            @if($hasPhoto && method_exists($user, 'getProfilePhotoUrlAttribute'))
                                <img src="{{ $user->profile_photo_url }}" alt="Profile" class="w-full h-full object-cover">
                            @else
                                {{ mb_substr($user->nama ?? 'A', 0, 1) }}
                            @endif
                        </div>
                        {{-- Heroicons: chevron-down (outline) - https://heroicons.com/ --}}
                        <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div id="adminProfileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200">
                        <a href="{{ route('admin.dashboard', ['modal' => 'profile']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 no-underline" id="adminProfileLink">
                            <div class="flex items-center gap-2">
                                {{-- Heroicons: user-circle (outline) - https://heroicons.com/ --}}
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
                                    {{-- Heroicons: arrow-right-on-rectangle (outline) - https://heroicons.com/ --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                                    </svg>
                                    <span>Keluar</span>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 px-6 py-8">
                @yield('content')
            </main>

        </div>
    </div>

    @include('partials.admin-profile-modal')
    
    <script>
        // Admin Profile Dropdown Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const profileDropdownButton = document.getElementById('adminProfileDropdownButton');
            const profileDropdown = document.getElementById('adminProfileDropdown');
            const profileDropdownContainer = document.getElementById('adminProfileDropdownContainer');
            const profileLink = document.getElementById('adminProfileLink');
            const profileModal = document.getElementById('adminProfileModalOverlay');
            
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

            // Open modal when profile link is clicked
            if (profileLink && profileModal) {
                profileLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    profileModal.classList.remove('hidden');
                    if (profileDropdown) {
                        profileDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>