@php
    $user = Auth::user();
    $isEditing = request('edit') === 'true' || old('_token');
    $joinDate = $user->created_at->format('F Y');
    $roleText = 'Admin';
    
    // Hitung statistik untuk admin
    $stats = [
        'total_kos' => \App\Models\Kos::count(),
        'total_users' => \App\Models\User::count(),
        'total_transaksi' => \App\Models\Payment::where('status', 'Verified')->sum('jumlah') ?? 0,
    ];
@endphp

@php
    $showModal = request('modal') === 'profile';
@endphp
<!-- Admin Profile Modal -->
<div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 {{ $showModal ? '' : 'hidden' }}" id="adminProfileModalOverlay">
    <div class="bg-white rounded-lg max-w-2xl w-full mx-4 p-8 relative shadow-2xl max-h-[90vh] overflow-y-auto">
        <button type="button" id="closeAdminProfileModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            {{-- Heroicons: x-mark (outline) - https://heroicons.com/ --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>

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
                                {{ mb_substr($user->nama, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $user->nama) }}"
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
                        {{-- Heroicons: document-check (outline) - https://heroicons.com/ --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 0 1 9 9v.375M10.125 2.25A3.375 3.375 0 0 1 13.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h4.125A3.375 3.375 0 0 1 22.125 11.25v9.375m0 0A3.375 3.375 0 0 1 18.75 24h-4.5A3.375 3.375 0 0 1 11.25 20.625v-9.375m9 0a3.375 3.375 0 0 0-3.375-3.375h-4.5a3.375 3.375 0 0 0-3.375 3.375m9 0v1.5c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125v-1.5m15 0h-4.5" />
                        </svg>
                        Simpan
                    </button>
                    <button
                        type="button"
                        id="cancelAdminProfileEdit"
                        class="flex items-center gap-2 px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition font-poppins"
                    >
                        {{-- Heroicons: x-mark (outline) - https://heroicons.com/ --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                        Batal
                    </button>
                </div>

                <!-- Profile Info -->
                <div class="space-y-4 mb-8 border-t border-b border-gray-200 py-6 font-poppins">
                    <div class="flex items-center gap-4">
                        {{-- Heroicons: envelope (outline) - https://heroicons.com/ --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1A4A7F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
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
                        {{-- Heroicons: phone (outline) - https://heroicons.com/ --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1A4A7F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">Telepon</p>
                            <input
                                type="tel"
                                name="phone"
                                value="{{ old('phone', $user->telepon) }}"
                                class="w-full font-medium text-gray-900 border-b border-gray-300 focus:outline-none focus:border-blue-900"
                            />
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        {{-- Heroicons: map-pin (outline) - https://heroicons.com/ --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1A4A7F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">Alamat</p>
                            <input
                                type="text"
                                name="address"
                                value="{{ old('address', $user->alamat) }}"
                                class="w-full font-medium text-gray-900 border-b border-gray-300 focus:outline-none focus:border-blue-900"
                            />
                        </div>
                    </div>
                </div>

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
                            {{ mb_substr($user->nama, 0, 1) }}
                        </div>
                    @endif
                </div>
                <h1 class="text-3xl font-bold text-gray-900 font-poppins">{{ $user->nama }}</h1>
                <p class="text-yellow-600 font-medium mt-2 font-poppins">Sebagai {{ $roleText }}</p>
            </div>

            <!-- Edit Button -->
            <div class="flex justify-center gap-2 mb-8">
                <button
                    type="button"
                    id="editAdminProfile"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition font-poppins"
                >
                    {{-- Heroicons: pencil-square (outline) - https://heroicons.com/ --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    Edit Profil
                </button>
            </div>

            <!-- Profile Info -->
            <div class="space-y-4 mb-8 border-t border-b border-gray-200 py-6 font-poppins">
                <div class="flex items-center gap-4">
                    {{-- Heroicons: envelope (outline) - https://heroicons.com/ --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1A4A7F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium text-gray-900">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    {{-- Heroicons: phone (outline) - https://heroicons.com/ --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1A4A7F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm text-gray-600">Telepon</p>
                        <p class="font-medium text-gray-900">{{ $user->telepon ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    {{-- Heroicons: map-pin (outline) - https://heroicons.com/ --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1A4A7F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm text-gray-600">Alamat</p>
                        <p class="font-medium text-gray-900">{{ $user->alamat ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 mb-8 py-6 border-t border-b border-gray-200 font-poppins">
                <div class="text-center">
                    <p class="text-3xl font-bold text-blue-900">{{ $stats['total_kos'] }}</p>
                    <p class="text-sm text-gray-600">Total Kos</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-blue-900">{{ $stats['total_users'] }}</p>
                    <p class="text-sm text-gray-600">Total Pengguna</p>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const profileModal = document.getElementById('adminProfileModalOverlay');
    const closeModal = document.getElementById('closeAdminProfileModal');
    const editButton = document.getElementById('editAdminProfile');
    const cancelEdit = document.getElementById('cancelAdminProfileEdit');

    // Check if modal should be shown on page load
    @if($showModal)
        if (profileModal) {
            profileModal.classList.remove('hidden');
        }
    @endif

    // Close modal
    if (closeModal) {
        closeModal.addEventListener('click', function() {
            const url = new URL(window.location.href);
            url.searchParams.delete('modal');
            url.searchParams.delete('edit');
            window.location.href = url.toString();
        });
    }

    // Close on overlay click
    if (profileModal) {
        profileModal.addEventListener('click', function(e) {
            if (e.target === profileModal) {
                const url = new URL(window.location.href);
                url.searchParams.delete('modal');
                url.searchParams.delete('edit');
                window.location.href = url.toString();
            }
        });
    }

    // Edit mode
    if (editButton) {
        editButton.addEventListener('click', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('modal', 'profile');
            url.searchParams.set('edit', 'true');
            window.location.href = url.toString();
        });
    }

    // Cancel edit
    if (cancelEdit) {
        cancelEdit.addEventListener('click', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('modal', 'profile');
            url.searchParams.delete('edit');
            window.location.href = url.toString();
        });
    }
});
</script>

