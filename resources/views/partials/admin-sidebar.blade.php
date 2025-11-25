<div class="h-full flex flex-col">
    <!-- Logo -->
    <div class="px-4 pt-4 pb-6 flex items-center justify-center" style="box-sizing: border-box; max-width: 100%;">
        @php
            $logoUrl = \App\Helpers\LogoHelper::getAdminLogoUrl();
        @endphp
        @if($logoUrl)
            <img src="{{ $logoUrl }}" alt="SiKosma" class="w-full h-auto rounded-lg flex-shrink-0 object-contain" style="max-width: 100%; padding: 0 8px;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
        @endif
        <!-- Fallback SVG Logo -->
        <div class="flex items-center gap-3 w-full px-2 {{ $logoUrl ? 'hidden' : 'flex' }}">
            <svg width="100%" height="auto" viewBox="0 0 200 60" fill="none" xmlns="http://www.w3.org/2000/svg" class="rounded-lg flex-shrink-0" preserveAspectRatio="xMidYMid meet">
                <rect width="200" height="60" fill="#0A3B65"/>
                <text x="100" y="40" font-family="Arial" font-size="32" font-weight="bold" fill="white" text-anchor="middle">S</text>
            </svg>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2">
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-yellow-400 bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
           {{-- Heroicons: home (solid) - https://heroicons.com/ --}}
            <svg class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
                <path d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />
                <path d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />
            </svg>
            Beranda
        </a>

        <a href="{{ route('admin.verifikasi-kos') }}" 
           class="flex items-center px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('admin.verifikasi-kos') ? 'text-yellow-400 bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
            {{-- Heroicons: check-circle (outline) - https://heroicons.com/ --}}
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Verifikasi Kos
        </a>

        <a href="{{ route('admin.verifikasi-pembayaran') }}" 
           class="flex items-center px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('admin.verifikasi-pembayaran*') ? 'text-yellow-400 bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
            {{-- Heroicons: currency-dollar (outline) - https://heroicons.com/ --}}
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Verifikasi Pembayaran
        </a>

        <a href="{{ route('admin.manajemen-pengguna') }}" 
           class="flex items-center px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('admin.manajemen-pengguna*') ? 'text-yellow-400 bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
            {{-- Heroicons: users (outline) - https://heroicons.com/ --}}
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>
            Manajemen Pengguna
        </a>

        <!-- Logout - dipindahkan ke dalam nav -->
        <form action="{{ route('logout') }}" method="POST" class="mt-2">
            @csrf
            <button type="submit" class="flex items-center w-full px-4 py-3 rounded-lg text-sm font-medium text-red-400 hover:text-red-300 hover:bg-red-500/10">
                {{-- Heroicons: arrow-right-on-rectangle (outline) - https://heroicons.com/ --}}
                <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                </svg>
                Keluar
            </button>
        </form>
    </nav>
</div>