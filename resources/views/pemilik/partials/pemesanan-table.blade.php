@if($bookings->count() > 0)
    <table class="w-full">
        <thead>
            <tr class="border-b">
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">ID Pesanan</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Nama Kos</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Kamar</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Tanggal Mulai</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Total Harga</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Status</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
                @php
                    // Format ID pesanan
                    $idPesanan = $booking->id_pemesanan ?? 'PES-' . strtoupper(substr($booking->id, 0, 8));
                    
                    // Format nama kos
                    $namaKos = $booking->kos->nama ?? '-';
                    
                    // Format kamar
                    $kamar = 'Kamar ' . ($booking->room->nomor_kamar ?? '-');
                    
                    // Format tanggal mulai
                    $tanggalMulai = $booking->tanggal_mulai ? $booking->tanggal_mulai->format('Y-m-d') : '-';
                    
                    // Format total harga
                    $totalHarga = 'Rp ' . number_format($booking->total_harga, 0, ',', '.');
                    
                    // Tentukan status berdasarkan booking status (yang sudah diupdate oleh admin saat approve/reject payment)
                    // Booking status adalah source of truth karena sudah diupdate oleh admin
                    $status = $booking->status ?? 'PENDING';
                    
                    // Hanya tampilkan CONFIRMED atau PENDING, jika status lain ubah ke PENDING
                    if ($status !== 'CONFIRMED' && $status !== 'PENDING') {
                        $status = 'PENDING';
                    }
                    
                    // Mapping status ke class dan icon
                    $statusConfig = [
                        'CONFIRMED' => [
                            'class' => 'bg-green-200 text-green-800',
                            'icon' => '<svg class="inline mr-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>'
                        ],
                        'PENDING' => [
                            'class' => 'bg-yellow-200 text-yellow-800',
                            'icon' => '<svg class="inline mr-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>'
                        ]
                    ];
                    
                    $statusInfo = $statusConfig[$status] ?? $statusConfig['PENDING'];
                    $statusClass = $statusInfo['class'];
                    $statusIcon = $statusInfo['icon'];
                    
                    // Data untuk modal
                    $bookingData = [
                        'id' => $booking->id,
                        'id_pemesanan' => $idPesanan,
                        'kos_name' => $namaKos,
                        'penyewa_nama' => $booking->user->nama ?? '-',
                        'room' => $kamar,
                        'tanggal_mulai' => $booking->tanggal_mulai ? $booking->tanggal_mulai->format('Y-m-d') : '-',
                        'tanggal_selesai' => $booking->tanggal_selesai ? $booking->tanggal_selesai->format('Y-m-d') : '-',
                        'total_harga' => $totalHarga,
                        'status' => $status,
                    ];
                @endphp
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4 text-sm text-gray-900 font-poppins font-semibold">{{ $idPesanan }}</td>
                    <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $namaKos }}</td>
                    <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $kamar }}</td>
                    <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $tanggalMulai }}</td>
                    <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $totalHarga }}</td>
                    <td class="py-3 px-4 text-sm">
                        <span class="px-3 py-1 rounded text-xs font-poppins inline-flex items-center {{ $statusClass }}">
                            {!! $statusIcon !!}
                            {{ $status }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <button
                            class="btn-view-detail text-blue-900 hover:text-blue-700 p-1"
                            data-booking="{{ json_encode($bookingData) }}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7Z" />
                            </svg>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="text-center py-12">
        <p class="text-gray-600 font-poppins">Tidak ada data pemesanan.</p>
    </div>
@endif
