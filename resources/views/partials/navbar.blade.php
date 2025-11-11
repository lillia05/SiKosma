<nav class="navbar navbar-expand-lg navbar-light" style="background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('beranda') }}" style="text-decoration: none;">
            @php
                $logoUrl = \App\Helpers\LogoHelper::getLogoUrl();
            @endphp
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="SiKosma Logo" class="me-2" style="height: 40px; width: auto;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
            @endif
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2" style="{{ $logoUrl ? 'display: none;' : '' }}">
                <path d="M20 5L35 15V30L20 40L5 30V15L20 5Z" fill="#1A4A7F"/>
                <path d="M20 10L30 17.5V27.5L20 35L10 27.5V17.5L20 10Z" fill="#FCD34D"/>
            </svg>
        </a>
        <div class="d-flex align-items-center" style="gap: 1.5rem;">
            <a href="{{ route('beranda') }}" class="font-poppins transition" style="color: {{ request()->routeIs('beranda') ? '#1A4A7F' : '#6B7280' }} !important; font-weight: {{ request()->routeIs('beranda') ? '600' : '500' }} !important; text-decoration: none !important; font-size: 1rem;">Beranda</a>
            <a href="{{ route('tentang') }}" class="font-poppins transition" style="color: {{ request()->routeIs('tentang') ? '#1A4A7F' : '#6B7280' }} !important; font-weight: {{ request()->routeIs('tentang') ? '600' : '500' }} !important; text-decoration: none !important; font-size: 1rem;">Tentang</a>
            @auth
                <a href="#" class="font-poppins transition" style="color: #6B7280 !important; font-weight: 500 !important; text-decoration: none !important; font-size: 1rem;">Riwayat</a>
            @endauth
            @auth
                <div class="dropdown">
                    <a class="dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #1A4A7F !important; font-weight: 500 !important; text-decoration: none !important; font-size: 1rem; cursor: pointer; gap: 0.5rem;">
                        @if(Auth::user()->profile_photo_url)
                            <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                        @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 32px; height: 32px; background: linear-gradient(to bottom right, #1A4A7F, #FCD34D); font-size: 0.9rem;">
                                {{ mb_substr(Auth::user()->name, 0, 1) }}
                            </div>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-start" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('beranda', ['modal' => 'profile']) }}">Profil</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">Keluar</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('beranda', ['modal' => 'login']) }}" class="btn btn-yellow" style="background-color: #FCD34D !important; color: #000 !important; border: none !important; padding: 8px 24px !important; border-radius: 8px !important; font-weight: 500 !important; cursor: pointer !important; font-size: 1rem; text-decoration: none !important; display: inline-block;">Masuk</a>
            @endauth
        </div>
    </div>
</nav>
