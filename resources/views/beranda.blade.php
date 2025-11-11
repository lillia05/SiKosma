@extends('layouts.app')

@section('title', 'Beranda - SiKosma')

@section('content')
<div class="container-lg my-5 px-1 px-md-2">
    <!-- Search Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex gap-2 mb-3">
                <input type="text" class="form-control search-bar flex-grow-1" placeholder="Masukkan nama kos/lokasi disini..." id="searchInput" value="{{ request('search') }}">
                <button class="btn btn-blue d-flex align-items-center justify-content-center gap-2 px-4" onclick="performSearch()">
                    <i class="bi bi-search"></i>
                    <span>Cari</span>
                </button>
            </div>
            
            <!-- Filter Buttons -->
            <div class="d-flex flex-wrap justify-content-center gap-2 mt-2">
                <button class="filter-btn {{ !request('lokasi') && !request('type') ? 'active' : 'inactive' }}" onclick="clearFilters()">Semua</button>
                <button class="filter-btn {{ request('lokasi') == 'Kampung Baru' ? 'active' : 'inactive' }}" onclick="filterByLocation('Kampung Baru')">Kampung Baru</button>
                <button class="filter-btn {{ request('lokasi') == 'Gedong Meneng' ? 'active' : 'inactive' }}" onclick="filterByLocation('Gedong Meneng')">Gedong Meneng</button>
                <button class="filter-btn {{ request('type') == 'Putra' ? 'active' : 'inactive' }}" onclick="filterByType('Putra')">Kos Putra</button>
                <button class="filter-btn {{ request('type') == 'Putri' ? 'active' : 'inactive' }}" onclick="filterByType('Putri')">Kos Putri</button>
                <button class="filter-btn {{ request('type') == 'Campur' ? 'active' : 'inactive' }}" onclick="filterByType('Campur')">Kos Campur</button>
            </div>
        </div>
    </div>
    
    <!-- Kos Listings -->
    <div class="row">
        @forelse($kosList as $kos)
            <div class="col-md-4 mb-4">
                <div class="kos-card">
                    <div class="position-relative">
                        @php
                            $mainImage = $kos->images->where('image_type', 'general')->first();
                            $imageUrl = $mainImage ? $mainImage->url : 'https://via.placeholder.com/400x200?text=' . urlencode($kos->name);
                        @endphp
                        <img src="{{ $imageUrl }}" alt="{{ $kos->name }}" class="kos-image" onerror="this.src='https://via.placeholder.com/400x200?text={{ urlencode($kos->name) }}'">
                        <div class="position-absolute top-0 end-0 p-2">
                            <div class="kos-tag">{{ $kos->city }}</div>
                            <div class="kos-tag kos-tag-white mt-1">{{ $kos->type }}</div>
                        </div>
                    </div>
                    <div class="p-3">
                        <h5 class="fw-bold text-primary">{{ $kos->name }}</h5>
                        <p class="text-muted small mb-2">{{ $kos->address }}</p>
                        <div class="d-flex align-items-center mb-2">
                            <span class="star-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= round($kos->rating) ? '-fill' : '' }}"></i>
                                @endfor
                            </span>
                            <span class="ms-2 text-muted small">{{ number_format($kos->rating, 1) }} / 5 ({{ $kos->total_reviews }})</span>
                        </div>
                        @php
                            $availableRooms = $kos->rooms->where('status', 'Tersedia')->count();
                            $minPrice = $kos->rooms->where('status', 'Tersedia')->min('price_per_year');
                        @endphp
                        @if($minPrice)
                            <p class="fw-bold text-primary mb-1">Rp{{ number_format($minPrice, 0, ',', '.') }} / Tahun</p>
                        @endif
                        <p class="text-muted small mb-3">Jumlah Kamar Tersedia: {{ $availableRooms }}</p>
                        <a href="#" class="btn btn-blue w-100">Lihat Detail</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Tidak ada kos yang ditemukan.</p>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($kosList->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $kosList->links('pagination::bootstrap-4') }}
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

