@extends('layouts.app')

@section('title', 'Pembayaran - SiKosma')

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
                <a href="{{ route('kos.booking', $kos->id) }}" class="text-primary-blue hover:underline">Penyewaan</a>
                <span class="mx-2">›</span>
                <span class="text-primary-blue font-semibold">Pembayaran</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 max-w-7xl mx-auto px-4 py-8 w-full">
        <h1 class="text-3xl font-bold text-primary-blue mb-8 font-poppins">PEMBAYARAN</h1>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Panel - Summary -->
            <div class="bg-white rounded-lg p-6 space-y-6">
                <h2 class="text-xl font-bold text-primary-blue font-poppins">Ringkasan Penyewaan</h2>

                <div class="relative h-48 w-full bg-gray-200 rounded-lg overflow-hidden">
                    <img src="{{ $roomImageUrl }}" alt="Room" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/400x300?text=Kamar+{{ $room->nomor_kamar }}'">
                </div>

                <div class="space-y-3 pb-6 border-b">
                    <div>
                        <p class="text-sm text-gray-600 font-poppins">Nama Kos</p>
                        <p class="font-bold text-gray-900 font-poppins">: {{ $kos->nama }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-poppins">Nomor Kamar</p>
                        <p class="font-bold text-gray-900 font-poppins">: {{ $room->nomor_kamar }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-poppins">Harga/Tahun</p>
                        <p class="font-bold text-gray-900 font-poppins">: Rp{{ number_format($room->harga_per_tahun, 0, ',', '.') }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-poppins">Mulai Sewa</p>
                        <p class="font-bold text-gray-900 font-poppins">: {{ $tanggalMulai }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-poppins">Durasi Sewa</p>
                        <p class="font-bold text-gray-900 font-poppins">: {{ $bookingData['durasi_tahun'] }} Tahun</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-poppins">Jatuh Tempo</p>
                        <p class="font-bold text-gray-900 font-poppins">: {{ $tanggalJatuhTempo }}</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-primary-blue mb-4 font-poppins">Rekening Tujuan Pembayaran:</h3>
                    <div class="bg-gray-50 p-4 rounded-lg space-y-2 font-poppins">
                        <p>
                            <strong>Bank Tujuan</strong> : Bank Negara Indonesia (BNI)
                        </p>
                        <p>
                            <strong>No. Rekening</strong> : 0123456789
                        </p>
                        <p>
                            <strong>Atas Nama</strong> : PT SiKosma
                        </p>
                    </div>
                </div>

                <div class="pt-6 border-t">
                    <p class="text-sm text-gray-600 mb-2 font-poppins">Total Pembayaran:</p>
                    <p class="text-3xl font-bold text-primary-blue font-poppins">
                        Rp{{ number_format($bookingData['total_harga'], 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <!-- Right Panel - Payment Form -->
            <div class="bg-white rounded-lg p-6 space-y-6 h-fit sticky top-24">
                <h2 class="text-xl font-bold text-primary-blue font-poppins">Konfirmasi Pembayaran</h2>

                @if(session('error') && session('show_login_modal'))
                    <div class="bg-red-50 border-2 border-red-200 rounded-lg p-4 mb-4">
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                            <div class="flex-1">
                                <h5 class="font-bold text-lg text-red-800 mb-1 font-poppins">Login Diperlukan</h5>
                                <p class="text-gray-700 mb-3 font-poppins">{{ session('error') }}</p>
                                <a href="{{ route('beranda', ['modal' => 'login']) }}" 
                                   class="inline-block bg-primary-blue text-white px-6 py-2 rounded-lg hover:bg-blue-900 transition font-poppins font-semibold no-underline">
                                    Login Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('pembayaran.store') }}" method="POST" enctype="multipart/form-data" {{ !Auth::check() ? 'onsubmit="event.preventDefault(); alert(\'Silakan login terlebih dahulu untuk melakukan pembayaran.\'); window.location.href=\'' . route('beranda', ['modal' => 'login']) . '\';"' : '' }}>
                    @csrf

                    <!-- Payment Method -->
                    <div>
                        <p class="text-sm font-bold text-gray-900 mb-3 font-poppins">Metode Pembayaran:</p>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer font-poppins">
                                <input
                                    type="radio"
                                    name="metode_pembayaran"
                                    value="Transfer Bank"
                                    {{ old('metode_pembayaran', 'Transfer Bank') == 'Transfer Bank' ? 'checked' : '' }}
                                    class="w-4 h-4"
                                />
                                <span>Transfer Bank</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer font-poppins">
                                <input
                                    type="radio"
                                    name="metode_pembayaran"
                                    value="E-Wallet"
                                    {{ old('metode_pembayaran') == 'E-Wallet' ? 'checked' : '' }}
                                    class="w-4 h-4"
                                />
                                <span>E-Wallet</span>
                            </label>
                        </div>
                        @error('metode_pembayaran')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sender Info -->
                    <div class="bg-gray-50 p-4 rounded-lg space-y-4">
                        <h3 class="text-sm font-bold text-gray-900 font-poppins">Informasi Rekening Penyewa:</h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 font-poppins">Bank/E-Wallet Asal</label>
                            <select
                                name="nama_bank_pengirim"
                                required
                                class="w-full px-3 py-2 border {{ $errors->has('nama_bank_pengirim') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-blue font-poppins"
                            >
                                <option value="Bank Negara Indonesia (BNI)" {{ old('nama_bank_pengirim') == 'Bank Negara Indonesia (BNI)' ? 'selected' : '' }}>Bank Negara Indonesia (BNI)</option>
                                <option value="Bank Rakyat Indonesia (BRI)" {{ old('nama_bank_pengirim') == 'Bank Rakyat Indonesia (BRI)' ? 'selected' : '' }}>Bank Rakyat Indonesia (BRI)</option>
                                <option value="Bank Central Asia (BCA)" {{ old('nama_bank_pengirim') == 'Bank Central Asia (BCA)' ? 'selected' : '' }}>Bank Central Asia (BCA)</option>
                                <option value="Bank Mandiri" {{ old('nama_bank_pengirim') == 'Bank Mandiri' ? 'selected' : '' }}>Bank Mandiri</option>
                                <option value="CIMB Niaga" {{ old('nama_bank_pengirim') == 'CIMB Niaga' ? 'selected' : '' }}>CIMB Niaga</option>
                                <option value="Bank Tabungan Negara (BTN)" {{ old('nama_bank_pengirim') == 'Bank Tabungan Negara (BTN)' ? 'selected' : '' }}>Bank Tabungan Negara (BTN)</option>
                                <option value="Bank Danamon" {{ old('nama_bank_pengirim') == 'Bank Danamon' ? 'selected' : '' }}>Bank Danamon</option>
                                <option value="OVO" {{ old('nama_bank_pengirim') == 'OVO' ? 'selected' : '' }}>OVO</option>
                                <option value="GoPay" {{ old('nama_bank_pengirim') == 'GoPay' ? 'selected' : '' }}>GoPay</option>
                                <option value="DANA" {{ old('nama_bank_pengirim') == 'DANA' ? 'selected' : '' }}>DANA</option>
                                <option value="LinkAja" {{ old('nama_bank_pengirim') == 'LinkAja' ? 'selected' : '' }}>LinkAja</option>
                                <option value="ShopeePay" {{ old('nama_bank_pengirim') == 'ShopeePay' ? 'selected' : '' }}>ShopeePay</option>
                            </select>
                            @error('nama_bank_pengirim')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 font-poppins">
                                Nomor Rekening @error('nomor_rekening_pengirim')<span class="text-red-500">*</span>@enderror
                            </label>
                            <input
                                type="text"
                                name="nomor_rekening_pengirim"
                                value="{{ old('nomor_rekening_pengirim') }}"
                                placeholder="9876543210"
                                required
                                class="w-full px-3 py-2 border {{ $errors->has('nomor_rekening_pengirim') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-blue font-poppins"
                            />
                            @error('nomor_rekening_pengirim')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 font-poppins">
                                Atas Nama @error('nama_pengirim')<span class="text-red-500">*</span>@enderror
                            </label>
                            <input
                                type="text"
                                name="nama_pengirim"
                                value="{{ old('nama_pengirim', Auth::user()->nama ?? '') }}"
                                placeholder="Lekok Indah Lia"
                                required
                                class="w-full px-3 py-2 border {{ $errors->has('nama_pengirim') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-blue font-poppins"
                            />
                            @error('nama_pengirim')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Payment Proof Upload -->
                    <div>
                        <p class="text-sm font-bold text-gray-900 mb-3 font-poppins">Bukti Pembayaran:</p>

                        <!-- File input (selalu ada, tapi tersembunyi) -->
                        <input 
                            type="file" 
                            name="bukti_pembayaran" 
                            id="bukti_pembayaran"
                            accept="image/*" 
                            required
                            class="hidden"
                        />

                        <!-- Preview container (muncul setelah file dipilih) -->
                        <div id="previewContainer" class="hidden mb-3">
                            <div class="relative h-40 w-full bg-gray-200 rounded-lg overflow-hidden border-2 border-green-500">
                                <img id="previewImage" src="" alt="Payment proof" class="w-full h-full object-cover">
                                <button
                                    type="button"
                                    onclick="removePreview()"
                                    class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded hover:bg-red-600"
                                >
                                    {{-- Heroicons: x-mark (outline) - https://heroicons.com/ --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Upload label (hilang setelah file dipilih) -->
                        <label
                            id="uploadLabel"
                            for="bukti_pembayaran"
                            class="border-2 border-dashed {{ $errors->has('bukti_pembayaran') ? 'border-red-500' : 'border-gray-300' }} rounded-lg p-6 text-center cursor-pointer hover:bg-gray-50 transition block"
                        >
                            <div class="space-y-2">
                                {{-- Heroicons: arrow-up-tray (outline) - https://heroicons.com/ --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                </svg>
                                <p class="text-sm text-gray-600 font-poppins">Klik untuk upload bukti pembayaran</p>
                            </div>
                        </label>
                        @error('bukti_pembayaran')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Login Required Notification -->
                    @guest
                        <div class="bg-yellow-50 border-2 border-yellow-200 rounded-lg p-4 mb-4">
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                                <div class="flex-1">
                                    <h5 class="font-bold text-lg text-yellow-800 mb-1 font-poppins">Login Diperlukan</h5>
                                    <p class="text-gray-700 mb-3 font-poppins">Anda harus login terlebih dahulu untuk melakukan pembayaran.</p>
                                    <a href="{{ route('beranda', ['modal' => 'login']) }}" 
                                       class="inline-block bg-primary-blue text-white px-6 py-2 rounded-lg hover:bg-blue-900 transition font-poppins font-semibold no-underline">
                                        Login Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endguest

                    <!-- Buttons -->
                    <div class="flex gap-3 pt-6 border-t">
                        <a
                            href="{{ route('kos.booking', $kos->id) }}"
                            class="flex-1 border border-red-500 text-red-600 font-bold py-2 rounded-lg hover:bg-red-50 transition font-poppins text-center no-underline"
                        >
                            Batal
                        </a>
                        @auth
                            <button
                                type="submit"
                                class="flex-1 bg-primary-blue text-white font-bold py-2 rounded-lg hover:bg-blue-900 transition font-poppins"
                            >
                                Konfirmasi Pembayaran
                            </button>
                        @else
                            <button
                                type="button"
                                disabled
                                onclick="alert('Silakan login terlebih dahulu untuk melakukan pembayaran.'); window.location.href='{{ route('beranda', ['modal' => 'login']) }}';"
                                class="flex-1 bg-gray-400 text-white font-bold py-2 rounded-lg cursor-not-allowed font-poppins"
                            >
                                Konfirmasi Pembayaran (Login Diperlukan)
                            </button>
                        @endauth
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (!input || !input.files || !input.files[0]) {
            console.error('No file selected');
            return;
        }

        const file = input.files[0];
        
        // Validasi tipe file
        if (!file.type.match('image.*')) {
            alert('File harus berupa gambar!');
            input.value = '';
            return;
        }

        // Validasi ukuran file (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB!');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        
        reader.onload = function(e) {
            const previewImage = document.getElementById('previewImage');
            const previewContainer = document.getElementById('previewContainer');
            const uploadLabel = document.getElementById('uploadLabel');
            
            if (previewImage && previewContainer && uploadLabel) {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('hidden');
                uploadLabel.classList.add('hidden');
            } else {
                console.error('Preview elements not found');
            }
        };

        reader.onerror = function() {
            alert('Error membaca file!');
            input.value = '';
        };

        reader.readAsDataURL(file);
    }

    function removePreview() {
        const input = document.getElementById('bukti_pembayaran');
        const previewContainer = document.getElementById('previewContainer');
        const uploadLabel = document.getElementById('uploadLabel');
        
        // Reset file input
        if (input) {
            // Buat input baru untuk mereset value (karena input file tidak bisa di-reset langsung)
            const newInput = input.cloneNode(true);
            newInput.value = '';
            // Attach event listener untuk input baru
            newInput.addEventListener('change', function(e) {
                previewImage(e.target);
            });
            input.parentNode.replaceChild(newInput, input);
        }
        
        // Sembunyikan preview, tampilkan label upload
        if (previewContainer) {
            previewContainer.classList.add('hidden');
        }
        
        if (uploadLabel) {
            uploadLabel.classList.remove('hidden');
        }
    }

    // Pastikan fungsi tersedia saat DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('bukti_pembayaran');
        if (input) {
            // Attach event listener untuk file input
            input.addEventListener('change', function(e) {
                previewImage(e.target);
            });

            // Reset preview jika form di-reset
            const form = input.closest('form');
            if (form) {
                form.addEventListener('reset', function() {
                    removePreview();
                });
            }
        }
    });
</script>
@endsection

