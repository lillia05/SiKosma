@php
    $user = Auth::user();
    $isEditing = request('edit') === 'true' || old('_token');
    $joinDate = $user->created_at->format('F Y');
    $roleText = $user->role === 'pencari' ? 'Pencari Kos' : 'Pemilik Kos';
    
    // Tentukan route berdasarkan role
    $homeRoute = match($user->role) {
        'pemilik' => route('pemilik.dashboard'),
        'admin' => route('admin.dashboard'),
        default => route('pencari.beranda'),
    };
    
    // Hitung statistik
    $stats = [
        'pemesanan' => $user->role === 'pencari' ? $user->bookings()->count() : 0,
        'properti' => $user->role === 'pemilik' ? $user->kos()->count() : 0,
        'favorit' => 0, // TODO: implement favorit
        'penyewa' => 0, // TODO: implement penyewa
    ];
@endphp

<!-- Profile Modal -->
<div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 {{ request('modal') === 'profile' ? '' : 'hidden' }}" id="profileModalOverlay">
    <div class="bg-white rounded-lg max-w-2xl w-full mx-4 p-8 relative shadow-2xl max-h-[90vh] overflow-y-auto">
        <a href="{{ $homeRoute }}" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </a>

        @if($isEditing)
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Profile Header -->
                <div class="flex flex-col items-center mb-8">
                    <div class="w-24 h-24 rounded-full overflow-hidden mb-4 relative">
                        @if($user->profile_photo_url)
                            <img src="{{ $user->profile_photo_url }}" alt="Profile" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-900 to-yellow-400 flex items-center justify-center text-white text-5xl font-bold" style="background: linear-gradient(to bottom right, #1A4A7F, #FCD34D);">
                                {{ mb_substr($user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        class="text-3xl font-bold text-gray-900 text-center border-b-2 border-blue-900 focus:outline-none mb-2 w-full max-w-md font-poppins"
                        required
                    />
                    <p class="text-yellow-600 font-medium mt-2 font-poppins">Sebagai {{ $roleText }}</p>
                </div>

                <!-- Upload Foto Profile -->
                <div class="mb-8 border-t border-b border-gray-200 py-6 font-poppins">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profile</label>
                    <div class="flex flex-col items-center gap-3">
                        <input
                            type="file"
                            name="profile_photo"
                            id="profile_photo"
                            accept="image/*"
                            class="w-full max-w-xs px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 text-sm"
                        />
                        <p class="text-xs text-gray-500 text-center">Pilih foto untuk mengubah foto profile Anda</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-center gap-2 mb-8">
                    <button
                        type="submit"
                        class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-poppins"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Simpan
                    </button>
                    <a
                        href="{{ $homeRoute }}"
                        class="flex items-center gap-2 px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition font-poppins"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                        Batal
                    </a>
                </div>

                <!-- Profile Info -->
                <div class="space-y-4 mb-8 border-t border-b border-gray-200 py-6 font-poppins">
                    <div class="flex items-center gap-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1A4A7F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">Email</p>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                class="w-full font-medium text-gray-900 border-b border-gray-300 focus:outline-none focus:border-blue-900"
                                required
                            />
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1A4A7F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">Telepon</p>
                            <input
                                type="tel"
                                name="phone"
                                value="{{ old('phone', $user->phone) }}"
                                class="w-full font-medium text-gray-900 border-b border-gray-300 focus:outline-none focus:border-blue-900"
                            />
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1A4A7F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">Alamat</p>
                            <input
                                type="text"
                                name="address"
                                value="{{ old('address', $user->address) }}"
                                class="w-full font-medium text-gray-900 border-b border-gray-300 focus:outline-none focus:border-blue-900"
                            />
                        </div>
                    </div>
                </div>

                @if($user->role === 'pemilik')
                <!-- Bank Info -->
                <div class="space-y-4 mb-8 border-t border-b border-gray-200 py-6 font-poppins">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Rekening Bank</h3>

                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Nama Bank</p>
                            <select
                                name="bank_name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900"
                                required
                            >
                                <option value="">-- Pilih Bank --</option>
                                <option value="BRI" {{ old('bank_name', $user->bank_name) == 'BRI' ? 'selected' : '' }}>BRI (Bank Rakyat Indonesia)</option>
                                <option value="BCA" {{ old('bank_name', $user->bank_name) == 'BCA' ? 'selected' : '' }}>BCA (Bank Central Asia)</option>
                                <option value="BNI" {{ old('bank_name', $user->bank_name) == 'BNI' ? 'selected' : '' }}>BNI (Bank Negara Indonesia)</option>
                                <option value="Mandiri" {{ old('bank_name', $user->bank_name) == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                                <option value="CIMB" {{ old('bank_name', $user->bank_name) == 'CIMB' ? 'selected' : '' }}>CIMB Niaga</option>
                                <option value="OVO" {{ old('bank_name', $user->bank_name) == 'OVO' ? 'selected' : '' }}>OVO</option>
                                <option value="DANA" {{ old('bank_name', $user->bank_name) == 'DANA' ? 'selected' : '' }}>DANA</option>
                                <option value="LinkAja" {{ old('bank_name', $user->bank_name) == 'LinkAja' ? 'selected' : '' }}>LinkAja</option>
                            </select>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-2">Nomor Rekening/Akun</p>
                            <input
                                type="text"
                                name="account_number"
                                value="{{ old('account_number', $user->account_number) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 font-medium text-gray-900"
                                required
                            />
                        </div>
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded text-sm text-gray-700">
                        Informasi rekening ini digunakan untuk penerimaan pembayaran dari penyewa kos.
                    </div>
                </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <ul class="text-sm text-red-600 mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
        @else
            <!-- View Mode -->
            <!-- Profile Header -->
            <div class="flex flex-col items-center mb-8">
                <div class="w-24 h-24 rounded-full overflow-hidden mb-4 relative mx-auto">
                    @if($user->profile_photo_url)
                        <img src="{{ $user->profile_photo_url }}" alt="Profile" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-blue-900 to-yellow-400 flex items-center justify-center text-white text-5xl font-bold" style="background: linear-gradient(to bottom right, #1A4A7F, #FCD34D);">
                            {{ mb_substr($user->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <h1 class="text-3xl font-bold text-gray-900 font-poppins">{{ $user->name }}</h1>
                <p class="text-yellow-600 font-medium mt-2 font-poppins">Sebagai {{ $roleText }}</p>
            </div>

            <!-- Edit Button -->
            <div class="flex justify-center gap-2 mb-8">
                <a
                    href="{{ $homeRoute }}?modal=profile&edit=true"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition font-poppins"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Edit Profil
                </a>
            </div>

            <!-- Profile Info -->
            <div class="space-y-4 mb-8 border-t border-b border-gray-200 py-6 font-poppins">
                <div class="flex items-center gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1A4A7F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium text-gray-900">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1A4A7F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm text-gray-600">Telepon</p>
                        <p class="font-medium text-gray-900">{{ $user->phone ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1A4A7F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm text-gray-600">Alamat</p>
                        <p class="font-medium text-gray-900">{{ $user->address ?? '-' }}</p>
                    </div>
                </div>
            </div>

            @if($user->role === 'pemilik')
            <!-- Bank Info -->
            <div class="space-y-4 mb-8 border-t border-b border-gray-200 py-6 font-poppins">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Rekening Bank</h3>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-2">Nama Bank</p>
                        <p class="font-medium text-gray-900">{{ $user->bank_name ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-2">Nomor Rekening/Akun</p>
                        <p class="font-medium text-gray-900 tracking-widest">{{ $user->account_number ?? '-' }}</p>
                    </div>
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded text-sm text-gray-700">
                    Informasi rekening ini digunakan untuk penerimaan pembayaran dari penyewa kos.
                </div>
            </div>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 mb-8 py-6 border-t border-b border-gray-200 font-poppins">
                <div class="text-center">
                    <p class="text-3xl font-bold text-blue-900">{{ $user->role === 'pencari' ? $stats['pemesanan'] : $stats['properti'] }}</p>
                    <p class="text-sm text-gray-600">{{ $user->role === 'pencari' ? 'Pemesanan' : 'Properti' }}</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-blue-900">{{ $user->role === 'pencari' ? $stats['favorit'] : $stats['penyewa'] }}</p>
                    <p class="text-sm text-gray-600">{{ $user->role === 'pencari' ? 'Favorit' : 'Penyewa' }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600">Bergabung</p>
                    <p class="font-medium text-gray-900">{{ $joinDate }}</p>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-900 p-4 rounded font-poppins">
                <p class="text-sm text-gray-700">
                    Anda terdaftar sebagai <strong>{{ $roleText }}</strong>. Untuk mengubah role, silakan hubungi support.
                </p>
            </div>
        @endif
    </div>
</div>

