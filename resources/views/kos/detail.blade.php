@extends('layouts.app')

@section('title', 'Detail Kos - SiKosma')

@section('content')
<div class="min-h-screen bg-gray-100 flex flex-col">
    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="text-sm text-gray-600 font-poppins">
                <a href="{{ route('beranda') }}" class="text-primary-blue hover:underline">Beranda</a>
                <span class="mx-2">›</span>
                <span class="text-primary-blue font-semibold">Detail Kos</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-primary-blue mb-8 font-poppins">DETAIL KOS</h1>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Panel - Detail Kos -->
                <div class="bg-white rounded-lg p-6 space-y-6">
                    <!-- Main Image -->
                    @php
                        $mainImage = $kos->images->where('tipe_gambar', 'general')->first();
                        $imageUrl = $mainImage ? $mainImage->url : 'https://via.placeholder.com/600x400?text=' . urlencode($kos->nama);
                    @endphp
                    <div class="relative h-64 w-full bg-gray-300 rounded-lg overflow-hidden">
                        <img src="{{ $imageUrl }}" alt="{{ $kos->nama }}" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/600x400?text={{ urlencode($kos->nama) }}'">
                        <div class="absolute top-4 right-4 flex gap-2">
                            <span class="bg-primary-yellow text-primary-blue px-3 py-1 text-sm rounded font-medium font-poppins">
                                {{ $kos->kota }}
                            </span>
                            <span class="bg-white border-2 border-primary-yellow text-primary-blue px-3 py-1 text-sm rounded font-medium font-poppins">
                                {{ $kos->tipe }}
                            </span>
                        </div>
                    </div>

                    <!-- Gallery Thumbnails -->
                    @php
                        $allImages = $kos->images->take(4);
                        $remaining = 4 - $allImages->count();
                    @endphp
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($allImages as $image)
                            <div class="relative h-20 bg-gray-200 rounded cursor-pointer hover:opacity-75 overflow-hidden">
                                <img src="{{ $image->url }}" alt="Gallery" class="w-full h-full object-cover rounded" onerror="this.style.display='none'">
                            </div>
                        @endforeach
                        @for($i = 0; $i < $remaining; $i++)
                            <div class="relative h-20 bg-gray-200 rounded"></div>
                        @endfor
                    </div>

                    <!-- Rating and Info -->
                    <div class="flex items-center justify-between pb-6 border-b">
                        <div class="flex items-center gap-2">
                            <div class="flex text-primary-yellow">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($kos->rating))
                                        {{-- Heroicons: star (solid) - https://heroicons.com/ --}}
                                        <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        {{-- Heroicons: star (outline) - https://heroicons.com/ --}}
                                        <svg class="w-5 h-5 fill-current text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-sm text-gray-600 font-poppins">
                                {{ number_format($kos->rating, 1) }} / 5 ({{ $kos->total_ulasan }})
                            </span>
                        </div>
                        <span class="text-sm text-gray-600 font-poppins">
                            Jumlah Kamar Tersedia: {{ $kos->rooms->count() }}
                        </span>
                    </div>

                    <!-- Kos Name and Address -->
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2 font-poppins">{{ $kos->nama }}</h2>
                        <p class="text-gray-600 flex items-start gap-2 font-poppins">
                            {{-- Heroicons: map-pin (outline) - https://heroicons.com/ --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            <span>{{ $kos->alamat }}</span>
                        </p>
                    </div>

                    <!-- Fasilitas Kamar -->
                    @php
                        $firstRoom = $kos->rooms->first();
                        $fasilitas = $firstRoom ? explode(', ', $firstRoom->fasilitas) : [];
                    @endphp
                    @if($firstRoom && $firstRoom->fasilitas)
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-3 font-poppins">Fasilitas Kamar</h3>
                        <p class="text-gray-700 font-poppins">{{ $firstRoom->fasilitas }}</p>
                    </div>
                    @endif

                    <!-- Deskripsi -->
                    @if($kos->deskripsi)
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-3 font-poppins">Deskripsi</h3>
                        <p class="text-gray-700 leading-relaxed font-poppins">{{ $kos->deskripsi }}</p>
                    </div>
                    @endif

                    <!-- Contact Buttons -->
                    <div class="flex gap-3">
                        @if($kos->user && $kos->user->telepon)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $kos->user->telepon) }}?text=Halo,%20saya%20tertarik%20dengan%20{{ urlencode($kos->nama) }}" 
                           target="_blank"
                           class="flex-1 bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700 transition flex items-center justify-center gap-2 font-poppins no-underline">
                            {{-- Heroicons: chat-bubble-left-right (outline) - https://heroicons.com/ --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                            </svg>
                            <span>Tanya Pemilik Kos via WhatsApp</span>
                        </a>
                        @endif
                        @if($kos->tautan_google_maps)
                        <a href="{{ $kos->tautan_google_maps }}" 
                           target="_blank"
                           class="bg-primary-blue text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-900 transition flex items-center justify-center gap-2 font-poppins no-underline">
                            {{-- Heroicons: map-pin (outline) - https://heroicons.com/ --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                        </a>
                        @endif
                    </div>

                    <!-- Section Ulasan -->
                    <div class="border-t pt-6 mt-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-2xl font-bold text-gray-900 font-poppins flex items-center gap-2">
                                {{-- Heroicons: star (solid) - https://heroicons.com/ --}}
                                <svg class="w-7 h-7 text-primary-yellow fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                                </svg>
                                Ulasan & Rating
                            </h3>
                            @if($canReview && $userBooking && Auth::check() && Auth::user()->hasVerifiedEmail())
                                <a href="{{ route('ulasan.create', $kos->id) }}" 
                                   class="bg-primary-blue text-white px-4 py-2 rounded-lg hover:bg-blue-900 transition font-poppins no-underline flex items-center gap-2">
                                    {{-- Heroicons: pencil-square (outline) - https://heroicons.com/ --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                    Tulis Ulasan
                                </a>
                            @endif
                        </div>

                        <!-- Rating Summary -->
                        <div class="flex items-center gap-4 mb-6 pb-6 border-b">
                            <div class="text-5xl font-bold text-primary-blue font-poppins">{{ number_format($kos->rating, 1) }}</div>
                            <div class="flex-1">
                                <div class="flex text-primary-yellow mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($kos->rating))
                                            {{-- Heroicons: star (solid) - https://heroicons.com/ --}}
                                            <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            {{-- Heroicons: star (outline) - https://heroicons.com/ --}}
                                            <svg class="w-6 h-6 fill-current text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                                <p class="text-sm text-gray-600 font-poppins">{{ $kos->total_ulasan }} ulasan</p>
                            </div>
                        </div>

                        <!-- List Ulasan -->
                        <div class="space-y-6">
                            @forelse($kos->ulasan as $ulasan)
                                <div class="border-b pb-6 last:border-b-0">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-blue to-primary-yellow flex items-center justify-center text-white font-bold flex-shrink-0">
                                                {{ mb_substr($ulasan->user->nama ?? 'U', 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900 font-poppins">{{ $ulasan->user->nama ?? 'Pencari' }}</p>
                                                <p class="text-xs text-gray-500 font-poppins">{{ $ulasan->created_at->format('d F Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex text-primary-yellow">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $ulasan->rating)
                                                    {{-- Heroicons: star (solid) - https://heroicons.com/ --}}
                                                    <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    {{-- Heroicons: star (outline) - https://heroicons.com/ --}}
                                                    <svg class="w-5 h-5 fill-current text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    @if($ulasan->ulasan)
                                        <p class="text-gray-700 leading-relaxed font-poppins">{{ $ulasan->ulasan }}</p>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-gray-500 font-poppins">Belum ada ulasan untuk kos ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right Panel - Kamar Tersedia & Sewa -->
                <div class="space-y-6">
                    <div class="bg-white rounded-lg p-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 font-poppins">KAMAR TERSEDIA &amp; SEWA</h3>
                        <div class="space-y-4">
                            @forelse($kos->rooms as $room)
                                @php
                                    // Cari gambar kamar berdasarkan nomor kamar (format: kamar-{nomor_kamar}.png)
                                    $roomImage = $kos->images->where('tipe_gambar', 'kamar')
                                        ->filter(function($img) use ($room) {
                                            return str_contains($img->url_gambar, 'kamar-' . $room->nomor_kamar . '.png');
                                        })
                                        ->first();
                                    $roomImageUrl = $roomImage ? $roomImage->url : 'https://via.placeholder.com/400x300?text=Kamar+' . $room->nomor_kamar;
                                @endphp
                                <div class="border rounded-lg p-4">
                                    <div class="relative h-40 w-full bg-gray-200 rounded-lg mb-4 overflow-hidden">
                                        <img src="{{ $roomImageUrl }}" alt="Kamar {{ $room->nomor_kamar }}" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/400x300?text=Kamar+{{ $room->nomor_kamar }}'">
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2 font-poppins">Kamar {{ $room->nomor_kamar }}</p>
                                    <p class="text-2xl font-bold text-primary-blue mb-1 font-poppins">
                                        Rp{{ number_format($room->harga_per_tahun, 0, ',', '.') }}
                                    </p>
                                    <p class="text-sm text-gray-600 mb-4 font-poppins">/ Tahun</p>
                                    @auth
                                        @if(Auth::user()->hasVerifiedEmail())
                                            <a href="{{ route('kos.booking', ['id' => $kos->id, 'kamar' => $room->id]) }}" 
                                               class="w-full bg-primary-blue text-white font-bold py-2 rounded-lg hover:bg-blue-900 transition font-poppins block text-center no-underline">
                                                Sewa Sekarang
                                            </a>
                                        @else
                                            <a href="{{ route('verification.notice') }}" 
                                               class="w-full bg-gray-400 text-white font-bold py-2 rounded-lg cursor-not-allowed transition font-poppins block text-center no-underline"
                                               title="Verifikasi email Anda terlebih dahulu untuk melakukan booking">
                                                Verifikasi Email Dulu
                                            </a>
                                        @endif
                                    @else
                                        <button
                                            type="button"
                                            onclick="showLoginRequired()"
                                            class="w-full bg-primary-blue text-white font-bold py-2 rounded-lg hover:bg-blue-900 transition font-poppins"
                                        >
                                            Sewa Sekarang
                                        </button>
                                    @endauth
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-gray-600 font-poppins">Tidak ada kamar tersedia saat ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('error') && session('show_login_modal'))
    <div class="fixed top-4 right-4 bg-red-50 border-2 border-red-200 rounded-lg p-4 shadow-lg z-50 max-w-md">
        <div class="flex items-start gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            <div class="flex-1">
                <h5 class="font-bold text-lg text-red-800 mb-1 font-poppins">Login Diperlukan</h5>
                <p class="text-gray-700 mb-3 font-poppins">{{ session('error') }}</p>
                <a href="{{ route('beranda', ['modal' => 'login']) }}" 
                   class="inline-block bg-primary-blue text-white px-6 py-2 rounded-lg hover:bg-blue-900 transition font-poppins font-semibold no-underline">
                    Login Sekarang
                </a>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
@endif

<script>
    function showLoginRequired() {
        const message = 'Anda harus login terlebih dahulu untuk melakukan penyewaan.';
        alert(message);
        
        // Buka modal login
        const url = new URL(window.location.href);
        url.searchParams.set('modal', 'login');
        window.location.href = url.toString();
    }
</script>
@endsection

