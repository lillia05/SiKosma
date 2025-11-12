@extends('layouts.app')

@section('title', 'Beranda - SiKosma')

@section('content')
<div class="container mx-auto my-5 px-2 md:px-4 max-w-7xl">
    <!-- Search Section -->
    <div class="mb-6">
        <div class="flex gap-2 mb-3">
            <input type="text" class="search-bar flex-1" placeholder="Masukkan nama kos/lokasi disini..." id="searchInput" value="{{ request('search') }}">
            <button class="btn-blue flex items-center justify-center gap-2 px-4" onclick="performSearch()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span>Cari</span>
            </button>
        </div>
        
        <!-- Filter Buttons -->
        <div class="flex flex-wrap justify-center gap-2 mt-2">
            <button class="filter-btn {{ !request('lokasi') && !request('type') ? 'active' : 'inactive' }}" onclick="clearFilters()">Semua</button>
            <button class="filter-btn {{ request('lokasi') == 'Kampung Baru' ? 'active' : 'inactive' }}" onclick="filterByLocation('Kampung Baru')">Kampung Baru</button>
            <button class="filter-btn {{ request('lokasi') == 'Gedong Meneng' ? 'active' : 'inactive' }}" onclick="filterByLocation('Gedong Meneng')">Gedong Meneng</button>
            <button class="filter-btn {{ request('type') == 'Putra' ? 'active' : 'inactive' }}" onclick="filterByType('Putra')">Kos Putra</button>
            <button class="filter-btn {{ request('type') == 'Putri' ? 'active' : 'inactive' }}" onclick="filterByType('Putri')">Kos Putri</button>
            <button class="filter-btn {{ request('type') == 'Campur' ? 'active' : 'inactive' }}" onclick="filterByType('Campur')">Kos Campur</button>
        </div>
    </div>
    
    <!-- Kos Listings -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($kosList as $kos)
            <div class="kos-card">
                <div class="relative">
                    @php
                        $mainImage = $kos->images->where('image_type', 'general')->first();
                        $imageUrl = $mainImage ? $mainImage->url : 'https://via.placeholder.com/400x200?text=' . urlencode($kos->name);
                    @endphp
                    <img src="{{ $imageUrl }}" alt="{{ $kos->name }}" class="kos-image" onerror="this.src='https://via.placeholder.com/400x200?text={{ urlencode($kos->name) }}'">
                    <div class="absolute top-0 right-0 p-2">
                        <div class="kos-tag">{{ $kos->city }}</div>
                        <div class="kos-tag kos-tag-white mt-1">{{ $kos->type }}</div>
                    </div>
                </div>
                <div class="p-4">
                    <h5 class="font-bold text-primary-blue text-lg mb-2">{{ $kos->name }}</h5>
                    <p class="text-gray-600 text-sm mb-2">{{ $kos->address }}</p>
                    <div class="flex items-center mb-2">
                        <span class="star-rating flex">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($kos->rating))
                                    <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 fill-current text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @endif
                            @endfor
                        </span>
                        <span class="ml-2 text-gray-600 text-sm">{{ number_format($kos->rating, 1) }} / 5 ({{ $kos->total_reviews }})</span>
                    </div>
                    @php
                        $availableRooms = $kos->rooms->where('status', 'Tersedia')->count();
                        $minPrice = $kos->rooms->where('status', 'Tersedia')->min('price_per_year');
                    @endphp
                    @if($minPrice)
                        <p class="font-bold text-primary-blue mb-1">Rp{{ number_format($minPrice, 0, ',', '.') }} / Tahun</p>
                    @endif
                    <p class="text-gray-600 text-sm mb-3">Jumlah Kamar Tersedia: {{ $availableRooms }}</p>
                    <a href="#" class="btn-blue w-full text-center block no-underline">Lihat Detail</a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-600">Tidak ada kos yang ditemukan.</p>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($kosList->hasPages())
        <div class="flex justify-center mt-6">
            {{ $kosList->links() }}
        </div>
    @endif
</div>

<script>
    function performSearch() {
        const search = document.getElementById('searchInput').value;
        const url = new URL(window.location.href);
        if (search) {
            url.searchParams.set('search', search);
        } else {
            url.searchParams.delete('search');
        }
        window.location.href = url.toString();
    }
    
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
    
    function filterByLocation(location) {
        const url = new URL(window.location.href);
        url.searchParams.set('lokasi', location);
        url.searchParams.delete('type');
        window.location.href = url.toString();
    }
    
    function filterByType(type) {
        const url = new URL(window.location.href);
        url.searchParams.set('type', type);
        url.searchParams.delete('lokasi');
        window.location.href = url.toString();
    }
    
    function clearFilters() {
        window.location.href = '{{ route("beranda") }}';
    }
</script>
@endsection

