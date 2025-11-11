@extends('layouts.app')

@section('title', 'Tentang SiKosma')

@section('content')
<div class="flex-1 max-w-7xl mx-auto px-4 py-12 w-full">
    {{-- Bagian Misi --}}
    <section class="bg-white rounded-lg shadow-md p-8 mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-6 font-poppins">Tentang SiKosma</h1>

        <div class="space-y-6 font-poppins">
            <div>
                <h2 class="text-2xl font-bold text-blue-900 mb-4">Misi Kami</h2>
                <p class="text-gray-700 text-lg leading-relaxed">
                    SiKosma adalah platform yang membantu mahasiswa Universitas Lampung (Unila) mencari kos di sekitar
                    kampus dengan mudah dan efisien. Kami menyediakan informasi lengkap mengenai ketersediaan kamar, harga,
                    fasilitas, dan lokasi kos yang dekat dengan kampus Unila.
                </p>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-blue-900 mb-4">Fitur Aplikasi</h2>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start gap-3">
                        <span class="text-yellow-400 font-bold">✓</span>
                        <span>Pencarian kos berdasarkan lokasi, tipe, dan harga</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-yellow-400 font-bold">✓</span>
                        <span>Informasi detail setiap kos termasuk fasilitas dan rating</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-yellow-400 font-bold">✓</span>
                        <span>Pemesanan kamar langsung melalui aplikasi</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-yellow-400 font-bold">✓</span>
                        <span>Riwayat pemesanan dan status booking real-time</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-yellow-400 font-bold">✓</span>
                        <span>Gratis untuk semua mahasiswa (Pencari Kos dan Pemilik Kos)</span>
                    </li>
                </ul>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-blue-900 mb-4">Untuk Siapa?</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 border-l-4 border-blue-900 p-4 rounded">
                        <h3 class="font-bold text-blue-900 mb-2">Pencari Kos</h3>
                        <p class="text-gray-700 text-sm">
                            Mahasiswa Unila yang mencari tempat tinggal nyaman dengan informasi lengkap dan transparan
                        </p>
                    </div>
                    <div class="bg-green-50 border-l-4 border-green-600 p-4 rounded">
                        <h3 class="font-bold text-green-700 mb-2">Pemilik Kos</h3>
                        <p class="text-gray-700 text-sm">
                            Pemilik kos yang ingin mempromosikan kamar mereka kepada ribuan mahasiswa Unila
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Bagian Tim --}}
    <section class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center font-poppins">Tim Pengembang</h2>
        <p class="text-center text-gray-600 mb-12 font-poppins">
            SiKosma dikembangkan dengan penuh dedikasi oleh tim mahasiswa Unila yang passionate
        </p>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($teamMembers as $member)
                <div class="text-center font-poppins">
                    <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gradient-to-br from-blue-900 to-yellow-400 flex items-center justify-center text-white text-3xl font-bold" style="background: linear-gradient(to bottom right, #1A4A7F, #FCD34D);">
                        {{ mb_substr($member['name'], 0, 1) }}
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">{{ $member['name'] }}</h3>
                    <p class="text-sm text-gray-600">{{ $member['role'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-12 p-6 bg-yellow-50 border border-yellow-200 rounded-lg text-center font-poppins">
            <p class="text-gray-700 font-medium">
                Terima kasih telah memilih SiKosma sebagai platform pencarian kos Anda untuk membantu mahasiswa Unila
                menemukan kos impian mereka
            </p>
        </div>
    </section>
</div>
@endsection

