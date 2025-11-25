@extends('layouts.admin')

@section('title', 'Detail Pembayaran - SiKosma')

@section('content')
<div class="min-h-screen bg-gray-100 pb-12 w-full">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.verifikasi-pembayaran') }}" class="text-blue-900 hover:underline font-poppins flex items-center gap-2 no-underline">
                {{-- Heroicons: arrow-left (outline) - https://heroicons.com/ --}}
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Verifikasi Pembayaran
            </a>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-8 font-poppins">Detail Pembayaran</h1>

        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 font-poppins">Informasi Pembayaran</h2>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-sm text-gray-600 font-poppins mb-1">ID Pembayaran</p>
                    <p class="font-semibold text-gray-900 font-poppins">{{ $payment->booking->id_pemesanan ?? substr($payment->id, 0, 8) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-poppins mb-1">Status</p>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold font-poppins 
                        @if($payment->status === 'Pending')
                            bg-yellow-100 text-yellow-800
                        @elseif($payment->status === 'Verified')
                            bg-green-100 text-green-800
                        @else
                            bg-red-100 text-red-800
                        @endif">
                        @if($payment->status === 'Pending')
                            MENUNGGU
                        @elseif($payment->status === 'Verified')
                            DISETUJUI
                        @else
                            DITOLAK
                        @endif
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-poppins mb-1">Nominal</p>
                    <p class="font-semibold text-gray-900 font-poppins">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-poppins mb-1">Tanggal Pengajuan</p>
                    <p class="font-semibold text-gray-900 font-poppins">{{ $payment->created_at->format('d - m - Y') }}</p>
                </div>
            </div>

            <div class="border-t pt-4">
                <h3 class="text-lg font-bold text-gray-900 mb-3 font-poppins">Detail Transaksi</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600 font-poppins mb-1">Nama Kos</p>
                        <p class="font-semibold text-gray-900 font-poppins">{{ $payment->booking->kos->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-poppins mb-1">Kamar</p>
                        <p class="font-semibold text-gray-900 font-poppins">Kamar {{ $payment->booking->room->nomor_kamar ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-poppins mb-1">Pengguna</p>
                        <p class="font-semibold text-gray-900 font-poppins">{{ $payment->user->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-poppins mb-1">Metode Pembayaran</p>
                        <p class="font-semibold text-gray-900 font-poppins">{{ $payment->metode_pembayaran ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-poppins mb-1">Nama Bank Pengirim</p>
                        <p class="font-semibold text-gray-900 font-poppins">{{ $payment->nama_bank_pengirim ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-poppins mb-1">Nomor Rekening Pengirim</p>
                        <p class="font-semibold text-gray-900 font-poppins">{{ $payment->nomor_rekening_pengirim ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-poppins mb-1">Nama Pengirim</p>
                        <p class="font-semibold text-gray-900 font-poppins">{{ $payment->nama_pengirim ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bukti Pembayaran -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 font-poppins">Bukti Pembayaran</h2>
            @if($payment->proof_image_url)
                <div class="flex justify-center">
                    <div class="max-w-2xl w-full">
                        <img 
                            src="{{ $payment->proof_image_url }}" 
                            alt="Bukti Pembayaran" 
                            class="w-full h-auto rounded-lg border border-gray-300 shadow-md cursor-pointer hover:opacity-90 transition"
                            onclick="window.open('{{ $payment->proof_image_url }}', '_blank')"
                            onerror="this.onerror=null; this.src='https://via.placeholder.com/600x400?text=Gambar+Tidak+Ditemukan';"
                        >
                    </div>
                </div>
                <p class="text-center text-sm text-gray-500 mt-3 font-poppins">Klik gambar untuk melihat ukuran penuh</p>
            @else
                <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg">
                    <p class="text-gray-500 font-poppins">Bukti pembayaran tidak tersedia</p>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        @if($payment->status === 'Pending')
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <h2 class="text-xl font-bold text-gray-900 mb-4 font-poppins">Aksi</h2>
            <div class="flex gap-4">
                <form action="{{ route('admin.verifikasi-pembayaran.approve', $payment->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition font-poppins font-semibold">
                        Setujui Pembayaran
                    </button>
                </form>
                <form action="{{ route('admin.verifikasi-pembayaran.reject', $payment->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 transition font-poppins font-semibold">
                        Tolak Pembayaran
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle approve/reject buttons
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const action = this.action.includes('approve') ? 'menyetujui' : 'menolak';
            if (!confirm(`Apakah Anda yakin ingin ${action} pembayaran ini?`)) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endsection

