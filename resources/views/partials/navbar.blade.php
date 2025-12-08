<nav class="bg-white shadow-md">
    <div class="container mx-auto px-4 py-3">
        <div class="flex items-center justify-between">
            <a href="{{ route('beranda') }}" class="flex items-center gap-2 no-underline">
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
                <a href="{{ route('beranda') }}" class="font-poppins transition text-base no-underline {{ request()->routeIs('beranda') ? 'text-primary-blue font-semibold' : 'text-gray-600 font-medium' }} hover:underline">
                    Beranda
                </a>
                <a href="{{ route('tentang') }}" class="font-poppins transition text-base no-underline {{ request()->routeIs('tentang') ? 'text-primary-blue font-semibold' : 'text-gray-600 font-medium' }} hover:underline">
                    Tentang
                </a>
                @auth
                    @if(Auth::user()->hasVerifiedEmail())
                    <a href="{{ route('riwayat.index') }}" class="font-poppins transition text-base no-underline {{ request()->routeIs('riwayat.*') ? 'text-primary-blue font-semibold' : 'text-gray-600 font-medium' }} hover:underline">
                        Riwayat
                    </a>
                    @endif
                @endauth
                
                @auth
                    <!-- Profile Icon dengan Dropdown -->
                    <div class="relative" id="profileDropdownContainer">
                        <button type="button" id="profileDropdownButton" class="flex items-center gap-2 text-primary-blue font-medium text-base cursor-pointer focus:outline-none">
                            @if(Auth::user()->profile_photo_url)
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile" class="rounded-full w-8 h-8 object-cover">
                            @else
                                <div class="rounded-full w-8 h-8 flex items-center justify-center text-white font-bold text-sm bg-gradient-to-br from-primary-blue to-primary-yellow">
                                    {{ mb_substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            {{-- Heroicons: chevron-down (outline) - https://heroicons.com/ --}}
                            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                        <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200">
                            @if(Auth::user()->hasVerifiedEmail())
                            @php
                                // Tentukan route profile berdasarkan role
                                $profileRoute = match(Auth::user()->role) {
                                    'admin' => route('admin.dashboard', ['modal' => 'profile']),
                                    'pemilik' => route('pemilik.dashboard', ['modal' => 'profile']),
                                    'pencari' => route('pencari.beranda', ['modal' => 'profile']),
                                    default => route('beranda', ['modal' => 'profile']),
                                };
                            @endphp
                            <a href="{{ $profileRoute }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 no-underline">
                                <div class="flex items-center gap-2">
                                    {{-- Heroicons: user-circle (outline) - https://heroicons.com/ --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    <span>Profil</span>
                                </div>
                            </a>
                            <hr class="my-1 border-gray-200">
                            @endif
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
                @else
                    <a href="{{ route('beranda', ['modal' => 'login']) }}" class="btn-yellow no-underline">
                        Masuk
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
