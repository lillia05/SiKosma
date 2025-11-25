@extends('layouts.app')

@section('title', 'Tulis Ulasan - SiKosma')

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
                <span class="text-primary-blue font-semibold">Tulis Ulasan</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1">
        <div class="max-w-3xl mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-primary-blue mb-8 font-poppins">TULIS ULASAN</h1>

            <!-- Kos Info Card -->
            <div class="bg-white rounded-lg p-6 mb-6 shadow-sm">
                <h2 class="text-xl font-bold text-gray-900 mb-2 font-poppins">{{ $kos->nama }}</h2>
                <p class="text-gray-600 font-poppins">{{ $kos->alamat }}, {{ $kos->kota }}</p>
            </div>

            <!-- Form Ulasan -->
            <div class="bg-white rounded-lg p-6 shadow-sm">
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-600 font-poppins">{{ session('error') }}</p>
                    </div>
                @endif

                <form action="{{ route('ulasan.store', $kos->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_pemesanan" value="{{ $booking->id }}">

                    <!-- Rating -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3 font-poppins">Rating</label>
                        <div class="flex gap-2 items-center" id="ratingStars">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" 
                                        class="rating-star w-10 h-10 p-0 border-none bg-transparent cursor-pointer focus:outline-none transition" 
                                        data-rating="{{ $i }}">
                                    {{-- Heroicons: star (outline) - https://heroicons.com/ --}}
                                    <svg class="w-10 h-10 text-gray-300 fill-current star-empty" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                    </svg>
                                    {{-- Heroicons: star (solid) - https://heroicons.com/ --}}
                                    <svg class="w-10 h-10 text-primary-yellow fill-current star-filled hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @endfor
                            <span class="ml-3 text-sm font-medium text-gray-700 font-poppins" id="ratingText">Pilih rating</span>
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="0" required>
                        <p class="text-sm text-gray-500 mt-2 font-poppins">Klik bintang untuk memilih rating dari 1 sampai 5</p>
                        @error('rating')
                            <p class="text-red-600 text-sm mt-1 font-poppins">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ulasan Text -->
                    <div class="mb-6">
                        <label for="ulasan" class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Ulasan</label>
                        <textarea 
                            name="ulasan" 
                            id="ulasan" 
                            rows="6" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-blue font-poppins"
                            placeholder="Bagikan pengalaman Anda tentang kos ini..."
                            maxlength="1000">{{ old('ulasan') }}</textarea>
                        <p class="text-sm text-gray-500 mt-2 font-poppins">Maksimal 1000 karakter</p>
                        @error('ulasan')
                            <p class="text-red-600 text-sm mt-1 font-poppins">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <button type="submit" 
                                class="flex-1 bg-primary-blue text-white font-bold py-3 rounded-lg hover:bg-blue-900 transition font-poppins">
                            Kirim Ulasan
                        </button>
                        <a href="{{ route('kos.detail', $kos->id) }}" 
                           class="px-6 py-3 bg-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-400 transition font-poppins no-underline">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.rating-star');
    const ratingInput = document.getElementById('ratingInput');
    const ratingText = document.getElementById('ratingText');
    let selectedRating = 0;

    function updateStars(rating) {
        stars.forEach((star, index) => {
            const emptyStar = star.querySelector('.star-empty');
            const filledStar = star.querySelector('.star-filled');
            const ratingValue = index + 1;
            
            if (ratingValue <= rating) {
                // Show filled star
                if (emptyStar) emptyStar.classList.add('hidden');
                if (filledStar) filledStar.classList.remove('hidden');
            } else {
                // Show empty star
                if (emptyStar) emptyStar.classList.remove('hidden');
                if (filledStar) filledStar.classList.add('hidden');
            }
        });
        
        // Update rating text
        if (rating > 0) {
            ratingText.textContent = rating + ' bintang';
        } else {
            ratingText.textContent = 'Pilih rating';
        }
    }

    stars.forEach((star, index) => {
        star.addEventListener('click', function() {
            selectedRating = index + 1;
            ratingInput.value = selectedRating;
            updateStars(selectedRating);
        });

        star.addEventListener('mouseenter', function() {
            const hoverRating = index + 1;
            updateStars(hoverRating);
        });
    });

    // Reset to selected rating when mouse leaves
    const ratingContainer = document.getElementById('ratingStars');
    ratingContainer.addEventListener('mouseleave', function() {
        updateStars(selectedRating);
    });

    // Initial state - all empty
    updateStars(0);
});
</script>
@endsection
