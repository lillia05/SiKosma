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
    <div id="kosListContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @include('partials.kos-list')
    </div>
    
    <!-- Pagination -->
    <div id="paginationContainer">
        @include('partials.pagination')
    </div>
</div>

<script>
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const kosListContainer = document.getElementById('kosListContainer');
    const paginationContainer = document.getElementById('paginationContainer');
    let currentFilters = {
        search: '{{ request('search') }}',
        lokasi: '{{ request('lokasi') }}',
        type: '{{ request('type') }}'
    };

    // Real-time search dengan debounce (500ms)
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const searchValue = this.value.trim();
        
        searchTimeout = setTimeout(() => {
            currentFilters.search = searchValue;
            performAjaxSearch();
        }, 500);
    });

    // Search saat Enter atau klik tombol
    function performSearch() {
        const search = searchInput.value.trim();
        currentFilters.search = search;
        performAjaxSearch();
    }

    // AJAX Search
    function performAjaxSearch() {
        const params = new URLSearchParams();
        
        if (currentFilters.search) {
            params.set('search', currentFilters.search);
        }
        if (currentFilters.lokasi) {
            params.set('lokasi', currentFilters.lokasi);
        }
        if (currentFilters.type) {
            params.set('type', currentFilters.type);
        }

        // Update URL tanpa reload
        const newUrl = '{{ route("beranda") }}' + (params.toString() ? '?' + params.toString() : '');
        window.history.pushState({}, '', newUrl);

        // Show loading
        kosListContainer.innerHTML = '<div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12"><p class="text-gray-600">Mencari...</p></div>';
        paginationContainer.innerHTML = '';

        // AJAX Request
        fetch(newUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            kosListContainer.innerHTML = data.html;
            paginationContainer.innerHTML = data.pagination;
            
            // Re-attach pagination links untuk AJAX
            attachPaginationListeners();
        })
        .catch(error => {
            console.error('Error:', error);
            kosListContainer.innerHTML = '<div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12"><p class="text-red-600">Terjadi kesalahan saat mencari.</p></div>';
        });
    }

    // Filter by location
    function filterByLocation(location) {
        // Toggle filter
        if (currentFilters.lokasi === location) {
            currentFilters.lokasi = '';
        } else {
            currentFilters.lokasi = location;
            currentFilters.type = ''; // Remove type filter
        }
        
        // Update button states
        updateFilterButtons();
        performAjaxSearch();
    }

    // Filter by type
    function filterByType(type) {
        // Toggle filter
        if (currentFilters.type === type) {
            currentFilters.type = '';
        } else {
            currentFilters.type = type;
            currentFilters.lokasi = ''; // Remove location filter
        }
        
        // Update button states
        updateFilterButtons();
        performAjaxSearch();
    }

    // Clear all filters
    function clearFilters() {
        currentFilters = {
            search: '',
            lokasi: '',
            type: ''
        };
        searchInput.value = '';
        updateFilterButtons();
        performAjaxSearch();
    }

    // Update filter button states
    function updateFilterButtons() {
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
            btn.classList.add('inactive');
        });

        // Activate "Semua" if no filters
        if (!currentFilters.lokasi && !currentFilters.type) {
            document.querySelector('.filter-btn[onclick="clearFilters()"]')?.classList.remove('inactive');
            document.querySelector('.filter-btn[onclick="clearFilters()"]')?.classList.add('active');
        }

        // Activate location filter
        if (currentFilters.lokasi) {
            const locationBtn = Array.from(document.querySelectorAll('.filter-btn')).find(btn => 
                btn.textContent.trim() === currentFilters.lokasi
            );
            if (locationBtn) {
                locationBtn.classList.remove('inactive');
                locationBtn.classList.add('active');
            }
        }

        // Activate type filter
        if (currentFilters.type) {
            const typeBtn = Array.from(document.querySelectorAll('.filter-btn')).find(btn => 
                btn.textContent.trim() === 'Kos ' + currentFilters.type
            );
            if (typeBtn) {
                typeBtn.classList.remove('inactive');
                typeBtn.classList.add('active');
            }
        }
    }

    // Attach pagination listeners untuk AJAX
    function attachPaginationListeners() {
        document.querySelectorAll('#paginationContainer a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.href);
                const search = url.searchParams.get('search');
                const lokasi = url.searchParams.get('lokasi');
                const type = url.searchParams.get('type');
                
                if (search) currentFilters.search = search;
                if (lokasi) currentFilters.lokasi = lokasi;
                if (type) currentFilters.type = type;
                
                fetch(this.href, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    kosListContainer.innerHTML = data.html;
                    paginationContainer.innerHTML = data.pagination;
                    attachPaginationListeners();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            });
        });
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        updateFilterButtons();
        attachPaginationListeners();
        
        // Enter key untuk search
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSearch();
            }
        });
    });
</script>
@endsection

