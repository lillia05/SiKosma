@extends('layouts.pemilik')

@section('title', 'Daftar Pemesanan - SiKosma')

@section('content')
<div class="min-h-screen bg-gray-50 pb-12">
    <div class="max-w-7xl mx-auto px-6 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 font-poppins">Daftar Pemesanan</h1>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800 font-poppins">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Table Section -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="overflow-x-auto">
                @include('pemilik.partials.pemesanan-table', ['bookings' => $bookings])
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900 font-poppins">Detail Pesanan</h2>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
        </div>
        <div id="modalContent" class="space-y-3 text-sm">
            <!-- Content will be populated by JavaScript -->
        </div>
        <div class="mt-6">
            <button
                id="closeModalBtn"
                class="w-full bg-gray-200 text-gray-800 py-2 rounded hover:bg-gray-300 font-poppins">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const detailModal = document.getElementById('detailModal');
    const modalContent = document.getElementById('modalContent');
    const closeModal = document.getElementById('closeModal');
    const closeModalBtn = document.getElementById('closeModalBtn');

    // Attach event listeners for view detail buttons
    document.querySelectorAll('.btn-view-detail').forEach(button => {
        button.addEventListener('click', function() {
            const bookingData = JSON.parse(this.getAttribute('data-booking'));
            showDetailModal(bookingData);
        });
    });

    // Show detail modal
    function showDetailModal(booking) {
        const statusColors = {
            'CONFIRMED': 'bg-green-200 text-green-800',
            'PENDING': 'bg-yellow-200 text-yellow-800',
            'CANCELLED': 'bg-red-200 text-red-800'
        };

        const statusIcons = {
            'CONFIRMED': '<svg class="inline mr-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>',
            'PENDING': '<svg class="inline mr-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
            'CANCELLED': '<svg class="inline mr-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>'
        };

        const statusClass = statusColors[booking.status] || 'bg-gray-200 text-gray-800';
        const statusIcon = statusIcons[booking.status] || '';

        modalContent.innerHTML = `
            <div class="flex justify-between">
                <span class="text-gray-600 font-poppins">ID Pesanan:</span>
                <span class="font-semibold text-gray-900 font-poppins">${booking.id_pemesanan || booking.id}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600 font-poppins">Nama Kos:</span>
                <span class="font-semibold text-gray-900 font-poppins">${booking.kos_name || '-'}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600 font-poppins">Penyewa:</span>
                <span class="font-semibold text-gray-900 font-poppins">${booking.penyewa_nama || '-'}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600 font-poppins">Kamar:</span>
                <span class="font-semibold text-gray-900 font-poppins">${booking.room || '-'}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600 font-poppins">Periode:</span>
                <span class="font-semibold text-gray-900 font-poppins">${booking.tanggal_mulai || '-'} - ${booking.tanggal_selesai || '-'}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600 font-poppins">Total Harga:</span>
                <span class="font-semibold text-gray-900 font-poppins">${booking.total_harga || '-'}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600 font-poppins">Status:</span>
                <span class="px-3 py-1 rounded text-xs font-poppins ${statusClass}">
                    ${statusIcon}${booking.status}
                </span>
            </div>
        `;

        detailModal.classList.remove('hidden');
    }

    // Close modal
    function closeDetailModal() {
        detailModal.classList.add('hidden');
    }

    closeModal.addEventListener('click', closeDetailModal);
    closeModalBtn.addEventListener('click', closeDetailModal);
    detailModal.addEventListener('click', function(e) {
        if (e.target === detailModal) {
            closeDetailModal();
        }
    });
});
</script>
@endsection
