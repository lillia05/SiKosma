@forelse($kosList as $kos)
    <div class="kos-card">
        <div class="relative">
            @php
                $mainImage = $kos->images->where('image_type', 'general')->first();
                $imageUrl = $mainImage ? $mainImage->url : 'https://via.placeholder.com/400x200?text=' . urlencode($kos->name);
            @endphp
            <img src="{{ $imageUrl }}" alt="{{ $kos->name }}" class="kos-image" onerror="this.src='https://via.placeholder.com/400x200?text={{ urlencode($kos->name) }}'">
            <div class="absolute top-0 right-0 p-2">
                <div class="kos-tag">{{ $kos->city }}</div>
                <div class="kos-tag kos-tag-white mt-1">{{ $kos->type }}</div>
            </div>
        </div>
        <div class="p-4">
            <h5 class="font-bold text-primary-blue text-lg mb-2">{{ $kos->name }}</h5>
            <p class="text-gray-600 text-sm mb-2">{{ $kos->address }}</p>
            <div class="flex items-center mb-2">
                <span class="star-rating flex">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= round($kos->rating))
                            {{-- Heroicons: star (solid) - https://heroicons.com/ --}}
                            <svg class="w-4 h-4 fill-current text-primary-yellow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                            </svg>
                        @else
                            {{-- Heroicons: star (outline) - https://heroicons.com/ --}}
                            <svg class="w-4 h-4 fill-current text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                            </svg>
                        @endif
                    @endfor
                </span>
                <span class="ml-2 text-gray-600 text-sm">{{ number_format($kos->rating, 1) }} / 5 ({{ $kos->total_reviews }})</span>
            </div>
            @php
                // Kamar sudah difilter di controller, jadi langsung hitung dari rooms yang ada
                $availableRooms = $kos->rooms->count();
                $minPrice = $kos->rooms->min('price_per_year');
            @endphp
            @if($minPrice)
                <p class="font-bold text-primary-blue mb-1">Rp{{ number_format($minPrice, 0, ',', '.') }} / Tahun</p>
            @endif
            <p class="text-gray-600 text-sm mb-3">Jumlah Kamar Tersedia: {{ $availableRooms }}</p>
            <a href="{{ route('kos.detail', $kos->id) }}" class="btn-blue w-full text-center block no-underline">Lihat Detail</a>
        </div>
    </div>
@empty
    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12">
        <p class="text-gray-600">Tidak ada kos yang ditemukan.</p>
    </div>
@endforelse

