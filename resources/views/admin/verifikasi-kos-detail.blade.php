@extends('layouts.admin')

@section('title', 'Detail Kos - SiKosma')

@section('content')
<div class="min-h-screen bg-gray-100 pb-12 w-full">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.verifikasi-kos') }}" class="text-blue-900 hover:underline font-poppins flex items-center gap-2 no-underline">
                {{-- Heroicons: arrow-left (outline) - https://heroicons.com/ --}}
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Verifikasi Kos
            </a>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-8 font-poppins">DETAIL KOS</h1>

        <!-- Success/Error Message -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800 font-poppins">{{ session('success') }}</p>
            </div>
        @endif

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
                        <span class="bg-yellow-400 text-blue-900 px-3 py-1 text-sm rounded font-medium font-poppins">
                            {{ $kos->kota }}
                        </span>
                        <span class="bg-white border-2 border-yellow-400 text-blue-900 px-3 py-1 text-sm rounded font-medium font-poppins">
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
                        <div class="flex text-yellow-400">
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
                        Jumlah Kamar: {{ $kos->rooms->count() }}
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
                        <span>{{ $kos->alamat }}, {{ $kos->kota }}</span>
                    </p>
                </div>

                <!-- Informasi Pemilik -->
                @if($kos->user)
                <div class="border-t pt-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-3 font-poppins">Informasi Pemilik Kos</h3>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-600 font-poppins">Nama:</span>
                            <span class="text-sm font-semibold text-gray-900 font-poppins">{{ $kos->user->nama }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-600 font-poppins">Email:</span>
                            <span class="text-sm font-semibold text-gray-900 font-poppins">{{ $kos->user->email }}</span>
                        </div>
                        @if($kos->user->telepon)
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-600 font-poppins">Telepon:</span>
                            <span class="text-sm font-semibold text-gray-900 font-poppins">{{ $kos->user->telepon }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Deskripsi -->
                @if($kos->deskripsi)
                <div class="border-t pt-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-3 font-poppins">Deskripsi</h3>
                    <p class="text-gray-700 leading-relaxed font-poppins">{{ $kos->deskripsi }}</p>
                </div>
                @endif

                <!-- Informasi Kontak -->
                <div class="border-t pt-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-3 font-poppins">Informasi Kontak</h3>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-600 font-poppins">Nomor Telepon:</span>
                            <span class="text-sm font-semibold text-gray-900 font-poppins">{{ $kos->nomor_telepon }}</span>
                        </div>
                        @if($kos->tautan_google_maps)
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-600 font-poppins">Google Maps:</span>
                            <a href="{{ $kos->tautan_google_maps }}" target="_blank" class="text-sm text-blue-600 hover:underline font-poppins">
                                Lihat Lokasi
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Status -->
                <div class="border-t pt-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-3 font-poppins">Status</h3>
                    @php
                        $statusClass = '';
                        $statusText = '';
                        switch($kos->status) {
                            case 'Disetujui':
                                $statusClass = 'bg-green-100 text-green-800';
                                $statusText = 'DISETUJUI';
                                break;
                            case 'Ditolak':
                                $statusClass = 'bg-red-100 text-red-800';
                                $statusText = 'DITOLAK';
                                break;
                            case 'Menunggu':
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                $statusText = 'MENUNGGU';
                                break;
                            default:
                                $statusClass = 'bg-gray-100 text-gray-800';
                                $statusText = strtoupper($kos->status);
                        }
                    @endphp
                    <span class="px-4 py-2 rounded-full text-sm font-semibold font-poppins {{ $statusClass }}">
                        {{ $statusText }}
                    </span>
                </div>
            </div>

            <!-- Right Panel - Kamar Tersedia -->
            <div class="space-y-6">
                <div class="bg-white rounded-lg p-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 font-poppins">KAMAR TERSEDIA</h3>
                    <div class="space-y-4">
                        @forelse($kos->rooms as $room)
                            @php
                                // Cari gambar kamar berdasarkan nomor kamar
                                $roomImage = $kos->images->where('tipe_gambar', 'kamar')
                                    ->filter(function($img) use ($room) {
                                        return str_contains($img->url_gambar ?? $img->url, 'kamar-' . $room->nomor_kamar);
                                    })
                                    ->first();
                                $roomImageUrl = $roomImage ? $roomImage->url : 'https://via.placeholder.com/400x300?text=Kamar+' . $room->nomor_kamar;
                            @endphp
                            <div class="border rounded-lg p-4">
                                <div class="relative h-40 w-full bg-gray-200 rounded-lg mb-4 overflow-hidden">
                                    <img src="{{ $roomImageUrl }}" alt="Kamar {{ $room->nomor_kamar }}" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/400x300?text=Kamar+{{ $room->nomor_kamar }}'">
                                </div>
                                <p class="text-sm text-gray-600 mb-2 font-poppins">Kamar {{ $room->nomor_kamar }}</p>
                                <p class="text-2xl font-bold text-blue-900 mb-1 font-poppins">
                                    Rp{{ number_format($room->harga_per_tahun, 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-gray-600 mb-2 font-poppins">/ Tahun</p>
                                @if($room->fasilitas)
                                <p class="text-sm text-gray-600 mb-2 font-poppins">
                                    <strong>Fasilitas:</strong> {{ $room->fasilitas }}
                                </p>
                                @endif
                                <p class="text-sm font-semibold mb-2 font-poppins">
                                    Status: 
                                    <span class="{{ $room->status === 'Tersedia' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $room->status }}
                                    </span>
                                </p>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-gray-600 font-poppins">Tidak ada kamar untuk kos ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons (jika status Menunggu) -->
        @if($kos->status === 'Menunggu')
        <div class="mt-8 bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <h2 class="text-xl font-bold text-gray-900 mb-4 font-poppins">Aksi Verifikasi</h2>
            <div class="flex gap-4">
                <form action="{{ route('admin.verifikasi-kos.approve', $kos->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition font-poppins font-semibold">
                        Setujui Kos
                    </button>
                </form>
                <form action="{{ route('admin.verifikasi-kos.reject', $kos->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 transition font-poppins font-semibold">
                        Tolak Kos
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle approve/reject buttons
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const action = this.action.includes('approve') ? 'menyetujui' : 'menolak';
            if (!confirm(`Apakah Anda yakin ingin ${action} kos ini?`)) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endsection

