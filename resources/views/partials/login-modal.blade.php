@php
    // Tentukan apakah modal login harus ditampilkan
    // Tampilkan jika: ada error tanpa old('name') (error dari login) atau request modal=login
    // Pastikan tidak tampil jika modal register yang aktif
    $showLoginModal = (($errors->any() && old('name') == null) || request('modal') === 'login') && request('modal') !== 'register';
    
    // Tentukan step yang aktif: 'role' atau 'login'
    $currentStep = 'role';
    $currentRole = old('role', request('role', ''));
    
    if ($errors->any() && old('role') && old('name') == null) {
        // Jika ada error dan old('role') tapi bukan register, tampilkan login form
        $currentStep = 'login';
        $currentRole = old('role');
    } elseif (request('modal') === 'login' && request('role')) {
        $currentStep = 'login';
        $currentRole = request('role');
    }
@endphp

<!-- Login Modal -->
<div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 {{ $showLoginModal ? '' : 'hidden' }}" id="loginModalOverlay">
    <div class="bg-white rounded-lg max-w-md w-full mx-4 p-8 relative shadow-2xl">
        <a href="{{ route('beranda') }}" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </a>

        <!-- Role Selection Screen -->
        @if($currentStep === 'role')
        <div id="roleSelection">
            <h2 class="text-2xl font-bold text-gray-900 mb-2 font-poppins">Masuk ke SiKosma</h2>
            <p class="text-gray-600 mb-6 font-poppins">Saya ingin masuk sebagai</p>

            <div class="space-y-4">
                <a
                    href="{{ route('beranda', ['modal' => 'login', 'role' => 'pencari']) }}"
                    class="w-full border-2 border-gray-200 rounded-lg p-6 hover:border-blue-900 hover:bg-blue-50 transition text-left font-poppins block"
                >
                    <div class="font-bold text-gray-900 mb-2">Pencari Kos</div>
                    <div class="text-sm text-gray-600">Saya mencari kos yang sesuai</div>
                </a>

                <a
                    href="{{ route('beranda', ['modal' => 'login', 'role' => 'pemilik']) }}"
                    class="w-full border-2 border-gray-200 rounded-lg p-6 hover:border-blue-900 hover:bg-blue-50 transition text-left font-poppins block"
                >
                    <div class="font-bold text-gray-900 mb-2">Pemilik Kos</div>
                    <div class="text-sm text-gray-600">Saya mengelola kos</div>
                </a>
            </div>
        </div>
        @endif

        <!-- Login Form -->
        @if($currentStep === 'login')
        <div id="loginForm">
            <form id="loginFormElement" action="{{ route('login') }}" method="POST">
                @csrf
                <input type="hidden" name="role" value="{{ $currentRole }}">
                
                <h2 class="text-2xl font-bold text-gray-900 mb-2 font-poppins">
                    Masuk sebagai {{ $currentRole === 'pencari' ? 'Pencari Kos' : 'Pemilik Kos' }}
                </h2>
                <p class="text-gray-600 mb-6 text-sm font-poppins">
                    Masukkan kredensial Anda
                </p>

                <!-- Tombol Login dengan Google -->
                <div class="mb-4">
                    <a
                        href="{{ route('google.login', ['role' => $currentRole]) }}"
                        class="w-full flex items-center justify-center gap-3 border-2 border-gray-300 rounded-lg px-4 py-2.5 hover:bg-gray-50 transition font-poppins text-gray-700 font-medium no-underline"
                    >
                        <svg width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span>Masuk dengan Google</span>
                    </a>
                </div>

                <!-- Divider -->
                <div class="relative mb-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500 font-poppins">atau</span>
                    </div>
                </div>

                @if($errors->any() && old('_token') && old('name') == null)
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <ul class="text-sm text-red-600 mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Email</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 font-poppins @error('email') border-red-500 @enderror"
                            placeholder="your@email.com"
                            required
                        />
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Password</label>
                        <input
                            type="password"
                            name="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 font-poppins @error('password') border-red-500 @enderror"
                            placeholder="••••••••"
                            required
                        />
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-yellow-400 text-blue-900 font-bold py-2 rounded-lg hover:bg-yellow-500 transition font-poppins"
                    >
                        Masuk
                    </button>

                    <div class="text-center">
                        <p class="text-sm text-gray-600 font-poppins">
                            Belum punya akun?
                            <a
                                href="{{ route('beranda', ['modal' => 'register', 'role' => $currentRole]) }}"
                                class="text-blue-900 font-semibold hover:underline"
                            >
                                Daftar di sini
                            </a>
                        </p>
                    </div>

                    <a
                        href="{{ route('beranda', ['modal' => 'login']) }}"
                        class="w-full text-blue-900 text-sm hover:underline font-poppins block text-center"
                    >
                        Ganti Role
                    </a>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>

<script>
    // Fungsi untuk membuka modal (dipanggil dari navbar)
    window.openLoginModal = function() {
        window.location.href = "{{ route('beranda', ['modal' => 'login']) }}";
    };

    // Tutup modal saat klik di luar
    document.addEventListener('DOMContentLoaded', function() {
        const overlay = document.getElementById('loginModalOverlay');
        if (overlay) {
            overlay.addEventListener('click', function(e) {
                if (e.target === this) {
                    window.location.href = "{{ route('beranda') }}";
                }
            });
        }
    });
</script>
