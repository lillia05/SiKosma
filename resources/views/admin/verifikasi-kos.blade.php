@extends('layouts.admin')

@section('title', 'Verifikasi Kos - SiKosma')

@section('content')
<div class="min-h-screen bg-gray-100 pb-12 w-full">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 font-poppins flex items-center gap-2">
            {{-- Heroicons: check-circle (solid) - https://heroicons.com/ --}}
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8 text-blue-900">
                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
            </svg>
            VERIFIKASI KOS
        </h1>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-lg border border-gray-300 p-6 mb-8">
            <div class="flex gap-4 mb-6">
                <div class="flex-1 relative">
                    {{-- Heroicons: magnifying-glass (outline) - https://heroicons.com/ --}}
                    <svg class="absolute left-3 top-3 text-gray-400 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Masukkan nama kos..."
                        value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 font-poppins"
                    />
                </div>
                <button 
                    id="searchButton"
                    class="bg-blue-900 text-white px-8 py-3 rounded-lg hover:bg-blue-800 transition font-poppins font-semibold">
                    Cari
                </button>
            </div>

            <!-- Filter Buttons -->
            <div class="flex gap-2 mb-6">
                @php
                    $currentStatus = request('status', 'semua');
                @endphp
                @foreach(['semua' => 'Semua', 'Disetujui' => 'Disetujui', 'Menunggu' => 'Menunggu', 'Ditolak' => 'Ditolak'] as $status => $label)
                    <button
                        type="button"
                        data-status="{{ $status }}"
                        class="filter-status-btn px-6 py-2 rounded-full border-2 font-poppins text-sm font-semibold transition {{ $currentStatus === $status ? 'bg-blue-900 text-white border-blue-900' : ($status === 'Disetujui' ? 'border-green-500 text-green-700 hover:bg-green-50' : ($status === 'Menunggu' ? 'border-yellow-500 text-yellow-700 hover:bg-yellow-50' : ($status === 'Ditolak' ? 'border-red-500 text-red-700 hover:bg-red-50' : 'border-gray-300 text-gray-700 hover:bg-gray-50'))) }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800 font-poppins">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Table Section -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm overflow-x-auto">
            <h2 class="text-lg font-bold text-gray-900 mb-4 font-poppins">Daftar Pengajuan Kos</h2>
            <div id="kosTableContainer">
                @include('admin.partials.verifikasi-kos-table', ['kosList' => $kosList])
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const filterButtons = document.querySelectorAll('.filter-status-btn');
    const tableContainer = document.getElementById('kosTableContainer');
    
    let currentFilters = {
        search: '{{ request('search') }}',
        status: '{{ request('status', 'semua') }}'
    };

    // Search dengan debounce
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const searchValue = this.value.trim();
        
        searchTimeout = setTimeout(() => {
            currentFilters.search = searchValue;
            performSearch();
        }, 500);
    });

    // Search saat klik tombol atau Enter
    searchButton.addEventListener('click', performSearch);
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            performSearch();
        }
    });

    // Filter buttons
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active state
            filterButtons.forEach(b => {
                const status = b.getAttribute('data-status');
                b.classList.remove('bg-blue-900', 'text-white', 'border-blue-900');
                if (status === 'Disetujui') {
                    b.classList.add('border-green-500', 'text-green-700');
                } else if (status === 'Menunggu') {
                    b.classList.add('border-yellow-500', 'text-yellow-700');
                } else if (status === 'Ditolak') {
                    b.classList.add('border-red-500', 'text-red-700');
                } else {
                    b.classList.add('border-gray-300', 'text-gray-700');
                }
            });
            
            this.classList.add('bg-blue-900', 'text-white', 'border-blue-900');
            this.classList.remove('text-green-700', 'text-yellow-700', 'text-red-700', 'text-gray-700');
            
            currentFilters.status = this.getAttribute('data-status');
            performSearch();
        });
    });

    // AJAX Search
    function performSearch() {
        const params = new URLSearchParams();
        
        if (currentFilters.search) {
            params.set('search', currentFilters.search);
        }
        if (currentFilters.status && currentFilters.status !== 'semua') {
            params.set('status', currentFilters.status);
        }

        const url = '{{ route("admin.verifikasi-kos") }}' + (params.toString() ? '?' + params.toString() : '');
        
        // Update URL tanpa reload
        window.history.pushState({}, '', url);

        // Show loading
        tableContainer.innerHTML = '<div class="text-center py-12"><p class="text-gray-600 font-poppins">Mencari...</p></div>';

        // AJAX Request
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            tableContainer.innerHTML = data.html;
        })
        .catch(error => {
            console.error('Error:', error);
            tableContainer.innerHTML = '<div class="text-center py-12"><p class="text-red-600 font-poppins">Terjadi kesalahan saat mencari.</p></div>';
        });
    }

    // Handle approve/reject buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-approve')) {
            e.preventDefault();
            const form = e.target.closest('form');
            if (confirm('Apakah Anda yakin ingin menyetujui kos ini?')) {
                form.submit();
            }
        }
        
        if (e.target.closest('.btn-reject')) {
            e.preventDefault();
            const form = e.target.closest('form');
            if (confirm('Apakah Anda yakin ingin menolak kos ini?')) {
                form.submit();
            }
        }
    });
});
</script>
@endsection

