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
                    <div class="relative group">
                        <a href="#" class="flex items-center gap-2 text-primary-blue font-medium no-underline text-base cursor-pointer" onclick="event.preventDefault(); document.getElementById('profileDropdown').classList.toggle('hidden');">
                            @if(Auth::user()->profile_photo_url)
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile" class="rounded-full w-8 h-8 object-cover">
                            @else
                                <div class="rounded-full w-8 h-8 flex items-center justify-center text-white font-bold text-sm bg-gradient-to-br from-primary-blue to-primary-yellow">
                                    {{ mb_substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                        </a>
                        <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                            <a href="{{ route('beranda', ['modal' => 'profile']) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 no-underline">Profil</a>
                            <hr class="my-2">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">Keluar</button>
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
