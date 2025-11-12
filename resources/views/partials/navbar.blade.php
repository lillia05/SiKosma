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
                    <a href="#" class="font-poppins transition text-base no-underline text-gray-600 font-medium hover:underline">
                        Riwayat
                    </a>
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
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200">
                            <a href="{{ route('beranda', ['modal' => 'profile']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 no-underline">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>Profil</span>
                                </div>
                            </a>
                            <hr class="my-1 border-gray-200">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
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
