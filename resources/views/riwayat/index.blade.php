@extends('layouts.app')

@section('title', 'Riwayat Pemesanan - SiKosma')

@section('content')
<div class="min-h-screen bg-gray-100 flex flex-col">
    <div class="flex-1 max-w-7xl mx-auto px-4 py-8 w-full">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 font-poppins">Riwayat Pemesanan Saya</h1>

        <!-- Table -->
        <div class="bg-white rounded-lg overflow-hidden shadow">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900 font-poppins">ID Pesanan</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900 font-poppins">Kamar</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900 font-poppins">Tanggal Sewa</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900 font-poppins">Total Harga</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900 font-poppins">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900 font-poppins">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 font-poppins">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $booking->id_pemesanan }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            Kamar {{ $booking->room->nomor_kamar ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $booking->tanggal_mulai ? $booking->tanggal_mulai->format('Y-m-d') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">
                            Rp{{ number_format($booking->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @php
                                // Tentukan status berdasarkan payment status dan booking status
                                $bookingStatus = $booking->status;
                                $paymentStatus = $booking->payment->status ?? null;
                                
                                // Jika payment ditolak, status = DITOLAK
                                if ($paymentStatus === 'Rejected') {
                                    $displayStatus = 'DITOLAK';
                                    $statusClass = 'bg-red-200 text-red-900 font-semibold';
                                }
                                // Jika booking completed, status = SELESAI
                                elseif ($bookingStatus === 'COMPLETED') {
                                    $displayStatus = 'SELESAI';
                                    $statusClass = 'bg-green-100 text-green-900 font-semibold';
                                }
                                // Jika payment verified dan booking confirmed, status = DIKONFIRMASI
                                elseif ($paymentStatus === 'Verified' && $bookingStatus === 'CONFIRMED') {
                                    $displayStatus = 'DIKONFIRMASI';
                                    $statusClass = 'bg-green-200 text-green-900 font-semibold';
                                }
                                // Jika payment pending atau booking pending, status = MENUNGGU
                                elseif ($paymentStatus === 'Pending' || $bookingStatus === 'PENDING') {
                                    $displayStatus = 'MENUNGGU';
                                    $statusClass = 'bg-yellow-200 text-yellow-900 font-semibold';
                                }
                                // Jika booking cancelled
                                elseif ($bookingStatus === 'CANCELLED') {
                                    $displayStatus = 'DIBATALKAN';
                                    $statusClass = 'bg-gray-200 text-gray-800 font-semibold';
                                }
                                // Default
                                else {
                                    $displayStatus = strtoupper($bookingStatus);
                                    $statusClass = 'bg-gray-100 text-gray-800';
                                }
                            @endphp
                            <span class="px-3 py-1 rounded text-xs font-medium {{ $statusClass }}">
                                {{ $displayStatus }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @php
                                $hasUlasan = $booking->ulasan()->exists();
                                $canReview = $paymentStatus === 'Verified' && !$hasUlasan;
                            @endphp
                            @if($canReview)
                                <a href="{{ route('ulasan.create', $booking->kos->id) }}" 
                                   class="text-primary-blue hover:underline font-poppins flex items-center gap-1">
                                    {{-- Heroicons: pencil-square (outline) - https://heroicons.com/ --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                    Beri Ulasan
                                </a>
                            @elseif($hasUlasan)
                                <span class="text-gray-400 font-poppins flex items-center gap-1">
                                    {{-- Heroicons: check-circle (outline) - https://heroicons.com/ --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    Sudah Ulasan
                                </span>
                            @else
                                <span class="text-gray-400 font-poppins">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <p class="text-gray-600 text-lg font-poppins">Belum ada pemesanan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($bookings->hasPages())
        <div class="flex items-center justify-center gap-4 mt-6">
            @if($bookings->onFirstPage())
            <button disabled class="p-2 rounded border border-gray-300 opacity-50 cursor-not-allowed font-poppins">
                {{-- Heroicons: chevron-left (outline) - https://heroicons.com/ --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </button>
            @else
            <a href="{{ $bookings->previousPageUrl() }}" class="p-2 rounded border border-gray-300 hover:bg-gray-100 font-poppins">
                {{-- Heroicons: chevron-left (outline) - https://heroicons.com/ --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </a>
            @endif

            <span class="text-gray-700 font-medium font-poppins">
                Halaman {{ $bookings->currentPage() }} dari {{ $bookings->lastPage() }}
            </span>

            @if($bookings->hasMorePages())
            <a href="{{ $bookings->nextPageUrl() }}" class="p-2 rounded border border-gray-300 hover:bg-gray-100 font-poppins">
                {{-- Heroicons: chevron-right (outline) - https://heroicons.com/ --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </a>
            @else
            <button disabled class="p-2 rounded border border-gray-300 opacity-50 cursor-not-allowed font-poppins">
                {{-- Heroicons: chevron-right (outline) - https://heroicons.com/ --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </button>
            @endif
        </div>
        @endif

        <!-- Info Box -->
        <div class="bg-blue-50 border-l-4 border-blue-900 rounded p-4 mt-6 font-poppins">
            <p class="text-sm text-gray-700">
                Status <strong>"DIKONFIRMASI"</strong> Admin memverifikasi pembayaran. Status <strong>"AKTIF"</strong> saat masa sewa dimulai.
            </p>
        </div>
    </div>
</div>
@endsection

