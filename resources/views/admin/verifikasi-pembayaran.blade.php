@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran - SiKosma')

@section('content')
<div class="min-h-screen bg-gray-100 pb-12 w-full">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 font-poppins flex items-center gap-2">
            {{-- Heroicons: check-circle (solid) - https://heroicons.com/ --}}
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8 text-blue-900">
                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
            </svg>
            VERIFIKASI PEMBAYARAN
        </h1>

        <!-- Search Section -->
        <div class="bg-white rounded-lg border border-gray-300 p-6 mb-8">
            <div class="flex gap-4">
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
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800 font-poppins">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Table Section -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm overflow-x-auto">
            <h2 class="text-lg font-bold text-gray-900 mb-4 font-poppins">Tabel Transaksi</h2>
            <div id="paymentTableContainer">
                @include('admin.partials.verifikasi-pembayaran-table', ['payments' => $payments])
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const tableContainer = document.getElementById('paymentTableContainer');
    
    let currentSearch = '{{ request('search') }}';

    // Search dengan debounce
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const searchValue = this.value.trim();
        
        searchTimeout = setTimeout(() => {
            currentSearch = searchValue;
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

    // AJAX Search
    function performSearch() {
        const params = new URLSearchParams();
        
        if (currentSearch) {
            params.set('search', currentSearch);
        }

        const url = '{{ route("admin.verifikasi-pembayaran") }}' + (params.toString() ? '?' + params.toString() : '');
        
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
        if (e.target.closest('.btn-approve-payment')) {
            e.preventDefault();
            const form = e.target.closest('form');
            if (confirm('Apakah Anda yakin ingin menyetujui pembayaran ini?')) {
                form.submit();
            }
        }
        
        if (e.target.closest('.btn-reject-payment')) {
            e.preventDefault();
            const form = e.target.closest('form');
            if (confirm('Apakah Anda yakin ingin menolak pembayaran ini?')) {
                form.submit();
            }
        }
    });
});
</script>
@endsection

