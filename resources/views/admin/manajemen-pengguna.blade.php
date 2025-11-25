@extends('layouts.admin')

@section('title', 'Manajemen Pengguna - SiKosma')

@section('content')
<div class="min-h-screen bg-gray-100 pb-12 w-full">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 font-poppins flex items-center gap-2">
            {{-- Heroicons: users (outline) - https://heroicons.com/ --}}
            <svg class="h-8 w-8 text-blue-900" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>
            MANAJEMEN PENGGUNA
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
                        placeholder="Masukkan nama akun..."
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
            <div class="flex gap-2">
                @php
                    $currentRole = request('role', 'semua');
                @endphp
                @foreach(['semua' => 'Semua', 'pengguna' => 'Pengguna', 'pemilik kos' => 'Pemilik Kos'] as $role => $label)
                    <button
                        type="button"
                        data-role="{{ $role }}"
                        class="filter-role-btn px-6 py-2 rounded-full border-2 font-poppins text-sm font-semibold transition {{ $currentRole === $role ? 'bg-blue-900 text-white border-blue-900' : 'border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Success/Error Message -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800 font-poppins">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-800 font-poppins">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Table Section -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm overflow-x-auto">
            <h2 class="text-lg font-bold text-gray-900 mb-4 font-poppins">Daftar Akun Terdaftar</h2>
            <div id="usersTableContainer">
                @include('admin.partials.manajemen-pengguna-table', ['users' => $users])
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const filterButtons = document.querySelectorAll('.filter-role-btn');
    const tableContainer = document.getElementById('usersTableContainer');
    
    let currentFilters = {
        search: '{{ request('search') }}',
        role: '{{ request('role', 'semua') }}'
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
                b.classList.remove('bg-blue-900', 'text-white', 'border-blue-900');
                b.classList.add('border-gray-300', 'text-gray-700');
            });
            
            this.classList.add('bg-blue-900', 'text-white', 'border-blue-900');
            this.classList.remove('border-gray-300', 'text-gray-700');
            
            currentFilters.role = this.getAttribute('data-role');
            performSearch();
        });
    });

    // AJAX Search
    function performSearch() {
        const params = new URLSearchParams();
        
        if (currentFilters.search) {
            params.set('search', currentFilters.search);
        }
        if (currentFilters.role && currentFilters.role !== 'semua') {
            params.set('role', currentFilters.role);
        }

        const url = '{{ route("admin.manajemen-pengguna") }}' + (params.toString() ? '?' + params.toString() : '');
        
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

    // Handle status update via Edit button
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-edit-status')) {
            e.preventDefault();
            const button = e.target.closest('.btn-edit-status');
            const currentStatus = button.getAttribute('data-current-status');
            const newStatus = button.getAttribute('data-new-status');
            const statusText = newStatus === 'Aktif' ? 'AKTIF' : 'TIDAK AKTIF';
            
            if (confirm(`Apakah Anda yakin ingin mengubah status menjadi ${statusText}?`)) {
                const form = button.closest('form');
                form.submit();
            }
        }
    });

    // Handle delete button
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-delete-user')) {
            e.preventDefault();
            const form = e.target.closest('form');
            if (confirm('Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.')) {
                form.submit();
            }
        }
    });
});
</script>
@endsection

