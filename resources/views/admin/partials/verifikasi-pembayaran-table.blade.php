@if($payments->count() > 0)
    <table class="w-full">
        <thead>
            <tr class="border-b bg-gray-50">
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">ID</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Nama Kos</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Aktivitas</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Nominal</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Tanggal Pengajuan</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Status</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
                @php
                    $booking = $payment->booking;
                    $kos = $booking->kos ?? null;
                    $room = $booking->room ?? null;
                    
                    // Format status
                    $statusMap = [
                        'Pending' => 'MENUNGGU',
                        'Verified' => 'DISETUJUI',
                        'Rejected' => 'DITOLAK'
                    ];
                    $statusText = $statusMap[$payment->status] ?? strtoupper($payment->status);
                    
                    // Format tanggal
                    $tanggalPengajuan = $payment->created_at->format('d - m - Y');
                    
                    // Format nominal
                    $nominal = 'Rp ' . number_format($payment->jumlah, 0, ',', '.');
                    
                    // Aktivitas
                    $aktivitas = 'Pembayaran Kamar ' . ($room->nomor_kamar ?? '-');
                @endphp
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $booking->id_pemesanan ?? substr($payment->id, 0, 8) }}</td>
                    <td class="py-3 px-4 text-sm text-gray-900 font-poppins font-semibold">{{ $kos->nama ?? '-' }}</td>
                    <td class="py-3 px-4 text-sm text-gray-600 font-poppins">{{ $aktivitas }}</td>
                    <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $nominal }}</td>
                    <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $tanggalPengajuan }}</td>
                    <td class="py-3 px-4 text-sm">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold font-poppins 
                            @if($payment->status === 'Pending')
                                bg-yellow-100 text-yellow-800
                            @elseif($payment->status === 'Verified')
                                bg-green-100 text-green-800
                            @else
                                bg-red-100 text-red-800
                            @endif">
                            {{ $statusText }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-sm">
                        <div class="flex flex-wrap items-center gap-2">
                            @if($payment->status === 'Pending')
                                <form action="{{ route('admin.verifikasi-pembayaran.approve', $payment->id) }}" method="POST" class="inline-flex">
                                    @csrf
                                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-600 transition font-poppins btn-approve-payment">
                                        Setujui
                                    </button>
                                </form>
                                <form action="{{ route('admin.verifikasi-pembayaran.reject', $payment->id) }}" method="POST" class="inline-flex">
                                    @csrf
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600 transition font-poppins btn-reject-payment">
                                        Tolak
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('admin.verifikasi-pembayaran.detail', $payment->id) }}" class="flex items-center justify-center bg-blue-900 text-white px-3 py-1 rounded text-xs hover:bg-blue-800 transition font-poppins no-underline">
                                Lihat Detail
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="text-center py-12">
        <p class="text-gray-600 font-poppins">Tidak ada data pembayaran.</p>
    </div>
@endif

