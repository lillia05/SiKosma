@extends('layouts.pemilik')

@section('title', 'Manajemen Properti Kos Saya - SiKosma')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-900 font-poppins">Manajemen Properti Kos Saya</h1>
            <a href="{{ route('pemilik.kos.create') }}" class="bg-blue-900 text-white px-6 py-2 rounded-lg hover:bg-blue-800 flex items-center gap-2 font-poppins text-sm font-medium no-underline">
                + Tambah Kos Baru
            </a>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900 mb-4 font-poppins">Daftar Kos Saya</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">ID Kos</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Nama Kos</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Tipe</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Jumlah Kamar</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Status</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kosList as $kos)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $kos['kos_id'] }}</td>
                            <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $kos['nama'] }}</td>
                            <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $kos['tipe'] }}</td>
                            <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $kos['jumlah_kamar'] }}</td>
                            <td class="py-3 px-4">
                                <span class="px-3 py-1 rounded text-xs font-poppins {{ $kos['status_class'] }}">
                                    {{ $kos['status'] }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <button
                                        onclick="showDetailModal('{{ $kos['id'] }}', '{{ $kos['kos_id'] }}', '{{ addslashes($kos['nama']) }}', '{{ $kos['tipe'] }}', '{{ $kos['jumlah_kamar'] }}', '{{ $kos['status'] }}')"
                                        class="p-2 hover:bg-gray-100 rounded text-blue-900 hover:text-blue-700 transition"
                                        title="Lihat detail"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </button>
                                    <a
                                        href="{{ route('pemilik.kos.edit', $kos['id']) }}"
                                        class="p-2 hover:bg-gray-100 rounded text-blue-900 hover:text-blue-700 transition inline-block"
                                        title="Edit properti"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>
                                    <button
                                        onclick="deleteKos('{{ $kos['id'] }}', '{{ addslashes($kos['nama']) }}')"
                                        class="p-2 hover:bg-gray-100 rounded text-red-600 hover:text-red-800 transition"
                                        title="Hapus properti"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500 font-poppins">
                                Belum ada kos yang terdaftar. <a href="#" class="text-blue-900 hover:underline">Tambah kos baru</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Kos -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-lg max-w-md w-full p-6 shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900 font-poppins">Detail Kos</h2>
            <button onclick="closeDetailModal()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="space-y-3">
            <div>
                <p class="text-sm text-gray-600 font-poppins">ID Kos</p>
                <p class="text-sm font-medium text-gray-900 font-poppins" id="modalKosId">-</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-poppins">Nama Kos</p>
                <p class="text-sm font-medium text-gray-900 font-poppins" id="modalNama">-</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-poppins">Tipe Kos</p>
                <p class="text-sm font-medium text-gray-900 font-poppins" id="modalTipe">-</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-poppins">Jumlah Kamar</p>
                <p class="text-sm font-medium text-gray-900 font-poppins" id="modalJumlahKamar">-</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-poppins">Status</p>
                <p class="text-sm font-medium text-gray-900 font-poppins" id="modalStatus">-</p>
            </div>
        </div>
        <div class="mt-6 flex gap-2">
            <button
                onclick="editKosFromModal()"
                class="flex-1 bg-blue-900 text-white py-2 rounded hover:bg-blue-800 font-poppins text-sm font-medium"
            >
                Edit
            </button>
            <button
                onclick="closeDetailModal()"
                class="flex-1 bg-gray-200 text-gray-900 py-2 rounded hover:bg-gray-300 font-poppins text-sm font-medium"
            >
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentKosId = null;

    function showDetailModal(id, kosId, nama, tipe, jumlahKamar, status) {
        currentKosId = id;
        document.getElementById('modalKosId').textContent = kosId;
        document.getElementById('modalNama').textContent = nama;
        document.getElementById('modalTipe').textContent = tipe;
        document.getElementById('modalJumlahKamar').textContent = jumlahKamar;
        document.getElementById('modalStatus').textContent = status;
        document.getElementById('detailModal').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
        currentKosId = null;
    }

    function editKosFromModal() {
        if (currentKosId) {
            window.location.href = '{{ route("pemilik.kos.edit", ":id") }}'.replace(':id', currentKosId);
        }
    }

    function deleteKos(id, nama) {
        if (confirm(`Apakah Anda yakin ingin menghapus kos "${nama}"?`)) {
            // TODO: Implement delete functionality
            alert('Fitur hapus akan segera hadir!');
        }
    }

    // Close modal when clicking outside
    document.getElementById('detailModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDetailModal();
        }
    });
</script>
@endpush
@endsection

