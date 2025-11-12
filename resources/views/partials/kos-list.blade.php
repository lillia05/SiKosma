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
                            <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4 fill-current text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        @endif
                    @endfor
                </span>
                <span class="ml-2 text-gray-600 text-sm">{{ number_format($kos->rating, 1) }} / 5 ({{ $kos->total_reviews }})</span>
            </div>
            @php
                $availableRooms = $kos->rooms->where('status', 'Tersedia')->count();
                $minPrice = $kos->rooms->where('status', 'Tersedia')->min('price_per_year');
            @endphp
            @if($minPrice)
                <p class="font-bold text-primary-blue mb-1">Rp{{ number_format($minPrice, 0, ',', '.') }} / Tahun</p>
            @endif
            <p class="text-gray-600 text-sm mb-3">Jumlah Kamar Tersedia: {{ $availableRooms }}</p>
            <a href="#" class="btn-blue w-full text-center block no-underline">Lihat Detail</a>
        </div>
    </div>
@empty
    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12">
        <p class="text-gray-600">Tidak ada kos yang ditemukan.</p>
    </div>
@endforelse

