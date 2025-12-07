@extends('layouts.app')

@section('title', 'Form Penyewaan Kamar - SiKosma')

@section('content')
<div class="min-h-screen bg-gray-100 flex flex-col">
    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="text-sm text-gray-600 font-poppins">
                <a href="{{ route('beranda') }}" class="text-primary-blue hover:underline">Beranda</a>
                <span class="mx-2">›</span>
                <a href="{{ route('kos.detail', $kos->id) }}" class="text-primary-blue hover:underline">Detail Kos</a>
                <span class="mx-2">›</span>
                <span class="text-primary-blue font-semibold">Penyewaan</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 max-w-7xl mx-auto px-4 py-8 w-full">
        <h1 class="text-3xl font-bold text-primary-blue mb-8 font-poppins">FORM PENYEWAAN KAMAR</h1>
        
        <form action="{{ route('kos.booking.store', $kos->id) }}" method="POST">
            @csrf
            <input type="hidden" name="id_kamar" value="{{ $selectedRoom->id ?? '' }}">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Panel - Form -->
                <div class="bg-white rounded-lg p-6 space-y-6">
                    <h2 class="text-xl font-bold text-primary-blue font-poppins">Detail Kamar</h2>
                    
                    @if($selectedRoom)
                        @php
                            // Cari gambar kamar berdasarkan nomor kamar (format: kamar-{nomor_kamar}.png)
                            $roomImage = $kos->images->where('tipe_gambar', 'kamar')
                                ->filter(function($img) use ($selectedRoom) {
                                    return str_contains($img->url_gambar, 'kamar-' . $selectedRoom->nomor_kamar . '.png');
                                })
                                ->first();
                            $roomImageUrl = $roomImage ? $roomImage->url : 'https://via.placeholder.com/400x300?text=Kamar+' . $selectedRoom->nomor_kamar;
                        @endphp
                        <div class="relative h-48 w-full bg-gray-200 rounded-lg overflow-hidden">
                            <img src="{{ $roomImageUrl }}" alt="Room" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/400x300?text=Kamar+{{ $selectedRoom->nomor_kamar }}'">
                        </div>
                        
                        <div class="space-y-2 text-gray-600 font-poppins">
                            <p>
                                <strong>Nama Kos</strong> : {{ $kos->nama }}
                            </p>
                            <p>
                                <strong>Nomor Kamar</strong> : {{ $selectedRoom->nomor_kamar }}
                            </p>
                            <p>
                                <strong>Harga/Tahun</strong> : Rp{{ number_format($selectedRoom->harga_per_tahun, 0, ',', '.') }}
                            </p>
                        </div>
                    @endif

                    <!-- Start Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Tanggal Sewa</label>
                        <input
                            type="date"
                            name="tanggal_mulai"
                            value="{{ old('tanggal_mulai', date('Y-m-d')) }}"
                            min="{{ date('Y-m-d') }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-blue font-poppins"
                        >
                        @error('tanggal_mulai')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Duration -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Durasi Sewa (Tahun)</label>
                        <div class="flex items-center gap-4">
                            <button
                                type="button"
                                onclick="decreaseDuration()"
                                class="p-2 border border-gray-300 rounded hover:bg-gray-100"
                            >
                                {{-- Heroicons: minus (outline) - https://heroicons.com/ --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                </svg>
                            </button>
                            <input
                                type="number"
                                name="durasi_tahun"
                                id="durasi_tahun"
                                value="{{ old('durasi_tahun', 1) }}"
                                min="1"
                                max="10"
                                required
                                class="w-16 text-center border border-gray-300 rounded px-2 py-1 font-poppins"
                            >
                            <button
                                type="button"
                                onclick="increaseDuration()"
                                class="p-2 border border-gray-300 rounded hover:bg-gray-100"
                            >
                                {{-- Heroicons: plus (outline) - https://heroicons.com/ --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </button>
                        </div>
                        @error('durasi_tahun')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Button -->
                    @if($kos->user && $kos->user->telepon)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $kos->user->telepon) }}?text=Halo,%20saya%20tertarik%20dengan%20{{ urlencode($kos->nama) }}" 
                       target="_blank"
                       class="w-full bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700 transition flex items-center justify-center gap-2 font-poppins no-underline">
                        {{-- Heroicons: chat-bubble-left-right (outline) - https://heroicons.com/ --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                        </svg>
                        <span>Tanya Pemilik Kos</span>
                    </a>
                    @endif
                </div>

                <!-- Right Panel - Summary -->
                <div class="bg-white rounded-lg p-6 sticky top-24 h-fit font-poppins">
                    <h2 class="text-xl font-bold text-primary-blue mb-6">Ringkasan Penyewaan</h2>
                    
                    @if($selectedRoom)
                        @php
                            $startDate = old('tanggal_mulai', date('Y-m-d'));
                            $duration = old('durasi_tahun', 1);
                            $totalPrice = $selectedRoom->harga_per_tahun * $duration;
                            $endDate = date('Y-m-d', strtotime($startDate . ' + ' . $duration . ' years'));
                            
                            // Gunakan gambar kamar yang sama dari panel kiri
                            // $roomImageUrl sudah didefinisikan di atas
                        @endphp
                        
                        <div class="relative h-48 w-full bg-gray-200 rounded-lg mb-6 overflow-hidden">
                            <img src="{{ $roomImageUrl }}" alt="Room" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/400x300?text=Kamar+{{ $selectedRoom->nomor_kamar }}'">
                        </div>
                        
                        <div class="space-y-3 pb-6 border-b">
                            <div>
                                <p class="text-sm text-gray-600">Nama Kos</p>
                                <p class="font-bold text-gray-900">: {{ $kos->nama }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Nomor Kamar</p>
                                <p class="font-bold text-gray-900">: {{ $selectedRoom->nomor_kamar }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Harga/Tahun</p>
                                <p class="font-bold text-gray-900">: Rp{{ number_format($selectedRoom->harga_per_tahun, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Mulai Sewa</p>
                                <p class="font-bold text-gray-900" id="summary_start_date">: {{ $startDate ? date('d/m/Y', strtotime($startDate)) : '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Durasi Sewa</p>
                                <p class="font-bold text-gray-900" id="summary_duration">: {{ $duration }} Tahun</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Jatuh Tempo</p>
                                <p class="font-bold text-gray-900" id="summary_end_date">: {{ $startDate ? date('d/m/Y', strtotime($endDate)) : '-' }}</p>
                            </div>
                        </div>
                        
                        <div class="py-6">
                            <p class="text-sm text-gray-600 mb-2">Total Pembayaran:</p>
                            <p class="text-3xl font-bold text-primary-blue" id="summary_total">Rp{{ number_format($totalPrice, 0, ',', '.') }}</p>
                        </div>
                    @endif
                    
                    @auth
                        <button
                            type="submit"
                            class="w-full bg-primary-blue text-white font-bold py-3 rounded-lg hover:bg-blue-900 transition font-poppins"
                        >
                            Lanjut ke Pembayaran
                        </button>
                    @else
                        <div class="bg-yellow-50 border-2 border-yellow-200 rounded-lg p-4 mb-4">
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                                <div class="flex-1">
                                    <h5 class="font-bold text-lg text-yellow-800 mb-1 font-poppins">Login Diperlukan</h5>
                                    <p class="text-gray-700 mb-3 font-poppins">Anda harus login terlebih dahulu untuk melanjutkan ke pembayaran.</p>
                                    <a href="{{ route('beranda', ['modal' => 'login']) }}" 
                                       class="inline-block bg-primary-blue text-white px-6 py-2 rounded-lg hover:bg-blue-900 transition font-poppins font-semibold no-underline">
                                        Login Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                        <button
                            type="button"
                            disabled
                            class="w-full bg-gray-400 text-white font-bold py-3 rounded-lg cursor-not-allowed font-poppins"
                        >
                            Lanjut ke Pembayaran (Login Diperlukan)
                        </button>
                    @endauth
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function increaseDuration() {
        const input = document.getElementById('durasi_tahun');
        const currentValue = parseInt(input.value) || 1;
        if (currentValue < 10) {
            input.value = currentValue + 1;
            updateSummary();
        }
    }

    function decreaseDuration() {
        const input = document.getElementById('durasi_tahun');
        const currentValue = parseInt(input.value) || 1;
        if (currentValue > 1) {
            input.value = currentValue - 1;
            updateSummary();
        }
    }

    function updateSummary() {
        const duration = parseInt(document.getElementById('durasi_tahun').value) || 1;
        const startDateInput = document.querySelector('input[name="tanggal_mulai"]');
        const startDate = startDateInput.value;
        
        @if($selectedRoom)
            const pricePerYear = {{ $selectedRoom->harga_per_tahun }};
            const totalPrice = pricePerYear * duration;
            
            // Update summary
            document.getElementById('summary_duration').textContent = ': ' + duration + ' Tahun';
            document.getElementById('summary_total').textContent = 'Rp' + totalPrice.toLocaleString('id-ID');
            
            if (startDate) {
                const startDateObj = new Date(startDate);
                const endDateObj = new Date(startDateObj);
                endDateObj.setFullYear(startDateObj.getFullYear() + duration);
                
                const formatDate = (date) => {
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();
                    return day + '/' + month + '/' + year;
                };
                
                document.getElementById('summary_start_date').textContent = ': ' + formatDate(startDateObj);
                document.getElementById('summary_end_date').textContent = ': ' + formatDate(endDateObj);
            }
        @endif
    }

    // Update summary saat durasi atau tanggal berubah
    document.addEventListener('DOMContentLoaded', function() {
        const durationInput = document.getElementById('durasi_tahun');
        const startDateInput = document.querySelector('input[name="tanggal_mulai"]');
        
        durationInput.addEventListener('input', updateSummary);
        startDateInput.addEventListener('change', updateSummary);
    });
</script>
@endsection

