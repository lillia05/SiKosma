@extends('layouts.pemilik')

@section('title', isset($kos) ? 'Edit Kos - SiKosma' : 'Tambah Kos Baru - SiKosma')

@section('content')
<div class="min-h-screen bg-gray-50 pb-12">
    <div class="max-w-7xl mx-auto px-6 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 font-poppins">Form Tambah/Edit Kos</h1>

        <form id="kosForm" action="{{ isset($kos) ? route('pemilik.kos.update', $kos->id) : route('pemilik.kos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($kos))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Left - Form (3 columns) -->
                <div class="lg:col-span-3 space-y-6">
                    <!-- Informasi Dasar -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4 font-poppins">Informasi Dasar</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Nama Kos <span class="text-red-500">*</span></label>
                                <input
                                    type="text"
                                    name="nama"
                                    value="{{ old('nama', $kos->nama ?? '') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 font-poppins"
                                    placeholder="Contoh: Kos Nyaman Sentosa"
                                    required
                                />
                                @error('nama')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Tipe Kos <span class="text-red-500">*</span></label>
                                <div class="flex gap-6">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input
                                            type="radio"
                                            name="tipe"
                                            value="Putra"
                                            {{ old('tipe', $kos->tipe ?? 'Putra') === 'Putra' ? 'checked' : '' }}
                                            class="w-4 h-4"
                                            required
                                        />
                                        <span class="text-sm text-gray-700 font-poppins">Putra</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input
                                            type="radio"
                                            name="tipe"
                                            value="Putri"
                                            {{ old('tipe', $kos->tipe ?? '') === 'Putri' ? 'checked' : '' }}
                                            class="w-4 h-4"
                                        />
                                        <span class="text-sm text-gray-700 font-poppins">Putri</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input
                                            type="radio"
                                            name="tipe"
                                            value="Campur"
                                            {{ old('tipe', $kos->tipe ?? '') === 'Campur' ? 'checked' : '' }}
                                            class="w-4 h-4"
                                        />
                                        <span class="text-sm text-gray-700 font-poppins">Campur</span>
                                    </label>
                                </div>
                                @error('tipe')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Nomor Telepon <span class="text-red-500">*</span></label>
                                <input
                                    type="text"
                                    name="nomor_telepon"
                                    value="{{ old('nomor_telepon', $kos->nomor_telepon ?? '') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 font-poppins"
                                    placeholder="Contoh: 08123456789"
                                    required
                                />
                                @error('nomor_telepon')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Deskripsi Singkat</label>
                                <textarea
                                    name="deskripsi"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 font-poppins"
                                    rows="3"
                                    placeholder="Jelaskan kelebihan kos Anda, misalnya: lokasi strategis, dekat kampus, nyaman, dll"
                                >{{ old('deskripsi', $kos->deskripsi ?? '') }}</textarea>
                                @error('deskripsi')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Alamat & Lokasi -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4 font-poppins">Alamat & Lokasi</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Alamat Lengkap <span class="text-red-500">*</span></label>
                                <input
                                    type="text"
                                    name="alamat"
                                    value="{{ old('alamat', $kos->alamat ?? '') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 font-poppins"
                                    placeholder="Contoh: Jl. Merdeka No. 123, RT. 01 RW. 02"
                                    required
                                />
                                @error('alamat')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Kota <span class="text-red-500">*</span></label>
                                    <select
                                        name="kota"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 font-poppins"
                                        required
                                    >
                                        <option value="">Pilih Kota</option>
                                        <option value="Gedung Meneng" {{ old('kota', $kos->kota ?? '') === 'Gedung Meneng' ? 'selected' : '' }}>Gedung Meneng</option>
                                        <option value="Kampung Baru" {{ old('kota', $kos->kota ?? '') === 'Kampung Baru' ? 'selected' : '' }}>Kampung Baru</option>
                                    </select>
                                    @error('kota')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Link Google Maps</label>
                                    <input
                                        type="url"
                                        name="tautan_google_maps"
                                        value="{{ old('tautan_google_maps', $kos->tautan_google_maps ?? '') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 font-poppins"
                                        placeholder="Tempel link Google Maps"
                                    />
                                    @error('tautan_google_maps')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Kamar -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-gray-900 font-poppins">Daftar Kamar</h2>
                            <button
                                type="button"
                                onclick="addRoom()"
                                class="bg-white border border-gray-300 text-gray-900 px-4 py-2 rounded-lg hover:bg-gray-50 flex items-center gap-2 font-poppins text-sm"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Tambah Kamar Baru
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full border">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Harga/Tahun</th>
                                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Luas (m²)</th>
                                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Fasilitas Kamar</th>
                                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Gambar Kamar</th>
                                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="roomsTableBody">
                                    @if(isset($kos) && $kos->rooms->count() > 0)
                                        @foreach($kos->rooms as $index => $room)
                                            @php
                                                $roomImage = $kos->images->where('tipe_gambar', 'kamar')
                                                    ->filter(function($img) use ($room) {
                                                        return str_contains($img->url_gambar, 'kamar-' . $room->nomor_kamar);
                                                    })
                                                    ->first();
                                            @endphp
                                            <tr class="border-b room-row" data-room-index="{{ $index }}">
                                                <td class="py-3 px-4">
                                                    <input
                                                        type="hidden"
                                                        name="rooms[{{ $index }}][id]"
                                                        value="{{ $room->id }}"
                                                    />
                                                    <input
                                                        type="hidden"
                                                        name="rooms[{{ $index }}][nomor_kamar]"
                                                        value="{{ $room->nomor_kamar }}"
                                                        class="room-nomor-kamar"
                                                    />
                                                    <input
                                                        type="number"
                                                        name="rooms[{{ $index }}][harga_per_tahun]"
                                                        value="{{ old("rooms.{$index}.harga_per_tahun", $room->harga_per_tahun) }}"
                                                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                                                        placeholder="Harga"
                                                        min="0"
                                                        step="1000"
                                                        required
                                                    />
                                                </td>
                                                <td class="py-3 px-4">
                                                    <input
                                                        type="number"
                                                        name="rooms[{{ $index }}][ukuran_kamar]"
                                                        value="{{ old("rooms.{$index}.ukuran_kamar", $room->ukuran_kamar) }}"
                                                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                                                        placeholder="Luas"
                                                        min="0"
                                                        step="0.01"
                                                        required
                                                    />
                                                </td>
                                                <td class="py-3 px-4">
                                                    <input
                                                        type="text"
                                                        name="rooms[{{ $index }}][fasilitas]"
                                                        value="{{ old("rooms.{$index}.fasilitas", $room->fasilitas) }}"
                                                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                                                        placeholder="Contoh: Kasur, AC, Lemari"
                                                    />
                                                </td>
                                                <td class="py-3 px-4">
                                                    <div class="flex items-center gap-2">
                                                        @if($roomImage)
                                                            <img src="{{ $roomImage->url }}" alt="Kamar {{ $room->nomor_kamar }}" class="w-12 h-12 object-cover rounded border border-gray-300 room-preview-image" data-room-nomor="{{ $room->nomor_kamar }}">
                                                        @else
                                                            <div class="w-12 h-12 bg-gray-200 rounded border border-gray-300 flex items-center justify-center room-preview-image" data-room-nomor="{{ $room->nomor_kamar }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400">
                                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                                    <polyline points="21 15 16 10 5 21"></polyline>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                        <input
                                                            type="file"
                                                            id="room-image-input-{{ $room->nomor_kamar }}"
                                                            name="room_images[{{ $room->nomor_kamar }}]"
                                                            accept="image/*"
                                                            class="room-image-input hidden"
                                                            data-room-nomor="{{ $room->nomor_kamar }}"
                                                            onchange="handleRoomImageUpload(this, '{{ $room->nomor_kamar }}')"
                                                        />
                                                        <button
                                                            type="button"
                                                            onclick="document.getElementById('room-image-input-{{ $room->nomor_kamar }}').click()"
                                                            class="text-xs text-blue-900 hover:text-blue-700 font-poppins cursor-pointer"
                                                        >
                                                            {{ $roomImage ? 'Ganti' : 'Upload' }}
                                                        </button>
                                                    </div>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <div class="flex gap-2">
                                                        <button
                                                            type="button"
                                                            onclick="removeRoom(this)"
                                                            class="p-1 text-gray-600 hover:text-red-600"
                                                            title="Hapus kamar"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <p class="text-sm text-gray-500 mt-2 font-poppins">* Minimal 1 kamar harus ditambahkan</p>
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-gray-800 text-white font-bold py-3 rounded-lg hover:bg-gray-900 font-poppins transition"
                    >
                        Simpan
                    </button>
                </div>

                <!-- Right - Image Gallery (1 column) -->
                <div class="lg:col-span-1 sticky top-8 h-fit">
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4 font-poppins">Galeri Foto Kos</h2>
                        <div class="space-y-4">
                            <!-- Drag and Drop Area -->
                            <div
                                id="dropZone"
                                class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg h-32 flex items-center justify-center cursor-pointer hover:bg-gray-50 transition"
                                onclick="document.getElementById('generalImagesInput').click()"
                            >
                                <div class="text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-2 text-gray-400">
                                        <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"></path>
                                        <circle cx="12" cy="13" r="3"></circle>
                                    </svg>
                                    <p class="text-xs text-gray-600 font-poppins">Drag & drop foto atau klik untuk upload</p>
                                </div>
                                <input
                                    type="file"
                                    id="generalImagesInput"
                                    name="general_images[]"
                                    multiple
                                    accept="image/*"
                                    class="hidden"
                                    onchange="handleGeneralImageUpload(this)"
                                />
                            </div>

                            <!-- Image Gallery Grid -->
                            <div id="imageGallery" class="grid grid-cols-2 gap-2">
                                @if(isset($kos) && $kos->images->where('tipe_gambar', 'general')->count() > 0)
                                    @foreach($kos->images->where('tipe_gambar', 'general') as $image)
                                        <div class="relative group existing-image" data-image-id="{{ $image->id }}">
                                            <img
                                                src="{{ $image->url }}"
                                                alt="Gallery"
                                                class="w-full h-24 object-cover rounded-lg border border-gray-300"
                                            />
                                            <input type="hidden" name="existing_general_images[]" value="{{ $image->id }}">
                                            <button
                                                type="button"
                                                onclick="removeExistingImage(this, '{{ $image->id }}')"
                                                class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <!-- Empty State -->
                            <div id="emptyState" class="text-center py-4 {{ (isset($kos) && $kos->images->where('tipe_gambar', 'general')->count() > 0) ? 'hidden' : '' }}">
                                <p class="text-sm text-gray-500 font-poppins">Belum ada foto yang di-upload</p>
                            </div>

                            <button
                                type="button"
                                onclick="document.getElementById('generalImagesInput').click()"
                                class="w-full bg-blue-900 text-white py-2 rounded-lg hover:bg-blue-800 flex items-center justify-center gap-2 font-poppins transition"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                                Upload Foto
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let roomCounter = {{ isset($kos) && $kos->rooms->count() > 0 ? $kos->rooms->count() : 0 }};
    let deletedImageIds = [];
    let deletedRoomIds = [];

    // Drag and Drop untuk General Images
    const dropZone = document.getElementById('dropZone');
    const generalImagesInput = document.getElementById('generalImagesInput');

    if (dropZone) {
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.add('border-blue-500', 'bg-blue-50');
        });

        dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
            
            const files = Array.from(e.dataTransfer.files);
            const imageFiles = files.filter(file => file.type.startsWith('image/'));
            
            if (imageFiles.length > 0) {
                handleGeneralImageFiles(imageFiles);
            }
        });
    }

    // Handle General Image Upload
    function handleGeneralImageUpload(input) {
        if (input.files && input.files.length > 0) {
            const files = Array.from(input.files);
            handleGeneralImageFiles(files);
        }
    }

    function handleGeneralImageFiles(files) {
        const imageGallery = document.getElementById('imageGallery');
        const emptyState = document.getElementById('emptyState');
        
        files.forEach(file => {
            if (!file.type.startsWith('image/')) return;
            
            const reader = new FileReader();
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.className = 'relative group new-image';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" class="w-full h-24 object-cover rounded-lg border border-gray-300">
                    <button type="button" onclick="removeNewImage(this)" class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                `;
                imageGallery.appendChild(div);
                
                if (emptyState) {
                    emptyState.classList.add('hidden');
                }
            };
            reader.readAsDataURL(file);
        });
    }

    function removeNewImage(button) {
        button.closest('.new-image').remove();
        const imageGallery = document.getElementById('imageGallery');
        const emptyState = document.getElementById('emptyState');
        
        if (imageGallery && imageGallery.querySelectorAll('.new-image, .existing-image').length === 0) {
            if (emptyState) {
                emptyState.classList.remove('hidden');
            }
        }
    }

    function removeExistingImage(button, imageId) {
        if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
            deletedImageIds.push(imageId);
            button.closest('.existing-image').remove();
            
            const imageGallery = document.getElementById('imageGallery');
            const emptyState = document.getElementById('emptyState');
            
            if (imageGallery && imageGallery.querySelectorAll('.new-image, .existing-image').length === 0) {
                if (emptyState) {
                    emptyState.classList.remove('hidden');
                }
            }
        }
    }

    // Add Room
    function addRoom() {
        roomCounter++;
        const tbody = document.getElementById('roomsTableBody');
        const newRow = document.createElement('tr');
        newRow.className = 'border-b room-row';
        newRow.setAttribute('data-room-index', roomCounter - 1);
        
        // Generate nomor kamar otomatis
        const existingNumbers = Array.from(tbody.querySelectorAll('.room-nomor-kamar'))
            .map(input => input.value)
            .filter(val => val);
        let newRoomNumber = roomCounter;
        while (existingNumbers.includes(String(newRoomNumber))) {
            newRoomNumber++;
        }
        
        newRow.innerHTML = `
            <td class="py-3 px-4">
                <input
                    type="hidden"
                    name="rooms[${roomCounter - 1}][nomor_kamar]"
                    value="${newRoomNumber}"
                    class="room-nomor-kamar"
                />
                <input
                    type="number"
                    name="rooms[${roomCounter - 1}][harga_per_tahun]"
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                    placeholder="Harga"
                    min="0"
                    step="1000"
                    required
                />
            </td>
            <td class="py-3 px-4">
                <input
                    type="number"
                    name="rooms[${roomCounter - 1}][ukuran_kamar]"
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                    placeholder="Luas"
                    min="0"
                    step="0.01"
                    required
                />
            </td>
            <td class="py-3 px-4">
                <input
                    type="text"
                    name="rooms[${roomCounter - 1}][fasilitas]"
                    class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                    placeholder="Contoh: Kasur, AC, Lemari"
                />
            </td>
            <td class="py-3 px-4">
                <div class="flex items-center gap-2">
                    <div class="w-12 h-12 bg-gray-200 rounded border border-gray-300 flex items-center justify-center room-preview-image" data-room-nomor="${newRoomNumber}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                    </div>
                    <input
                        type="file"
                        id="room-image-input-${newRoomNumber}"
                        name="room_images[${newRoomNumber}]"
                        accept="image/*"
                        class="room-image-input hidden"
                        data-room-nomor="${newRoomNumber}"
                        onchange="handleRoomImageUpload(this, '${newRoomNumber}')"
                    />
                    <button
                        type="button"
                        onclick="document.getElementById('room-image-input-${newRoomNumber}').click()"
                        class="text-xs text-blue-900 hover:text-blue-700 font-poppins cursor-pointer"
                    >
                        Upload
                    </button>
                </div>
            </td>
            <td class="py-3 px-4">
                <div class="flex gap-2">
                    <button
                        type="button"
                        onclick="removeRoom(this)"
                        class="p-1 text-gray-600 hover:text-red-600"
                        title="Hapus kamar"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(newRow);
    }

    // Remove Room
    function removeRoom(button) {
        const row = button.closest('.room-row');
        const roomIdInput = row.querySelector('input[name*="[id]"]');
        
        if (roomIdInput && roomIdInput.value) {
            if (confirm('Apakah Anda yakin ingin menghapus kamar ini?')) {
                deletedRoomIds.push(roomIdInput.value);
                row.remove();
            }
        } else {
            // New room, just remove
            row.remove();
        }
    }

    // Handle Room Image Upload
    function handleRoomImageUpload(input, nomorKamar) {
        if (!input) {
            console.error('Input element not found');
            return;
        }
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (!file.type.startsWith('image/')) {
                alert('File harus berupa gambar!');
                input.value = ''; // Reset input
                return;
            }
            
            // Check file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB!');
                input.value = ''; // Reset input
                return;
            }
            
            const reader = new FileReader();
            reader.onload = (e) => {
                const previewElement = document.querySelector(`.room-preview-image[data-room-nomor="${nomorKamar}"]`);
                if (previewElement) {
                    if (previewElement.tagName === 'IMG') {
                        previewElement.src = e.target.result;
                    } else {
                        // Replace div with img
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = `Kamar ${nomorKamar}`;
                        img.className = 'w-12 h-12 object-cover rounded border border-gray-300 room-preview-image';
                        img.setAttribute('data-room-nomor', nomorKamar);
                        previewElement.replaceWith(img);
                    }
                    
                    // Update button text
                    const td = input.closest('td');
                    if (td) {
                        const button = td.querySelector('button[type="button"]');
                        if (button) {
                            button.textContent = 'Ganti';
                        }
                    }
                }
            };
            reader.onerror = () => {
                alert('Gagal membaca file!');
                input.value = ''; // Reset input
            };
            reader.readAsDataURL(file);
        } else {
            console.warn('No file selected');
        }
    }

    // Form Validation
    document.getElementById('kosForm')?.addEventListener('submit', function(e) {
        const rooms = document.querySelectorAll('.room-row');
        if (rooms.length === 0) {
            e.preventDefault();
            alert('Minimal 1 kamar harus ditambahkan!');
            return false;
        }
        
        // Check if all rooms have required fields
        let hasError = false;
        rooms.forEach((room, index) => {
            const harga = room.querySelector('input[name*="[harga_per_tahun]"]');
            const ukuran = room.querySelector('input[name*="[ukuran_kamar]"]');
            
            if (!harga.value || parseFloat(harga.value) <= 0) {
                hasError = true;
                harga.focus();
            } else if (!ukuran.value || parseFloat(ukuran.value) <= 0) {
                hasError = true;
                ukuran.focus();
            }
        });
        
        if (hasError) {
            e.preventDefault();
            alert('Mohon lengkapi semua field kamar yang diperlukan!');
            return false;
        }
        
        // Add deleted image IDs to form
        if (deletedImageIds.length > 0) {
            deletedImageIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'deleted_image_ids[]';
                input.value = id;
                this.appendChild(input);
            });
        }
        
        // Add deleted room IDs to form
        if (deletedRoomIds.length > 0) {
            deletedRoomIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'deleted_room_ids[]';
                input.value = id;
                this.appendChild(input);
            });
        }
    });
</script>
@endpush
@endsection