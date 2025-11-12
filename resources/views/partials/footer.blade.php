<footer class="footer">
    <div class="container mx-auto text-center py-5">
        <div class="flex items-center justify-center gap-2 mb-2">
            @php
                $logoUrl = \App\Helpers\LogoHelper::getLogoUrl();
            @endphp
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="SiKosma Logo" class="h-8 w-auto" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
            @endif
            <svg width="30" height="30" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="{{ $logoUrl ? 'hidden' : '' }}">
                <path d="M20 5L35 15V30L20 40L5 30V15L20 5Z" fill="#1A4A7F"/>
                <path d="M20 10L30 17.5V27.5L20 35L10 27.5V17.5L20 10Z" fill="#FCD34D"/>
            </svg>
        </div>
        <p class="text-gray-600 mb-0">© 2025 SiKosma. All rights reserved.</p>
    </div>
</footer>
