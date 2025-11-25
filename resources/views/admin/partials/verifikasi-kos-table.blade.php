<table class="w-full">
    <thead>
        <tr class="border-b bg-gray-50">
            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">ID</th>
            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Nama Kos</th>
            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Alamat</th>
            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Tanggal Pengajuan</th>
            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Status</th>
            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($kosList as $kos)
        <tr class="border-b hover:bg-gray-50 transition">
            <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $loop->iteration }}</td>
            <td class="py-3 px-4 text-sm text-gray-900 font-poppins font-semibold">{{ $kos->nama }}</td>
            <td class="py-3 px-4 text-sm text-gray-600 font-poppins">{{ $kos->alamat }}</td>
            <td class="py-3 px-4 text-sm text-gray-900 font-poppins">
                @if($kos->created_at)
                    @php
                        $day = str_pad($kos->created_at->format('d'), 2, '0', STR_PAD_LEFT);
                        $month = str_pad($kos->created_at->format('m'), 2, '0', STR_PAD_LEFT);
                        $year = $kos->created_at->format('Y');
                    @endphp
                    {{ $day }} - {{ $month }} - {{ $year }}
                @else
                    -
                @endif
            </td>
            <td class="py-3 px-4 text-sm">
                @php
                    $statusClass = '';
                    $statusText = '';
                    switch($kos->status) {
                        case 'Disetujui':
                            $statusClass = 'bg-green-100 text-green-800';
                            $statusText = 'DISETUJUI';
                            break;
                        case 'Ditolak':
                            $statusClass = 'bg-red-100 text-red-800';
                            $statusText = 'DITOLAK';
                            break;
                        case 'Menunggu':
                            $statusClass = 'bg-yellow-100 text-yellow-800';
                            $statusText = 'MENUNGGU';
                            break;
                        default:
                            $statusClass = 'bg-gray-100 text-gray-800';
                            $statusText = strtoupper($kos->status);
                    }
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-semibold font-poppins {{ $statusClass }}">
                    {{ $statusText }}
                </span>
            </td>
            <td class="py-3 px-4 text-sm">
                <div class="flex gap-2">
                    @if($kos->status === 'Menunggu')
                        <form action="{{ route('admin.verifikasi-kos.approve', $kos->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn-approve bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition font-poppins text-xs">
                                Setujui
                            </button>
                        </form>
                        <form action="{{ route('admin.verifikasi-kos.reject', $kos->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn-reject bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition font-poppins text-xs">
                                Tolak
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('admin.verifikasi-kos.detail', $kos->id) }}" 
                       class="bg-blue-900 text-white px-3 py-1 rounded hover:bg-blue-800 transition font-poppins text-xs no-underline">
                        Lihat Detail
                    </a>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="py-12 text-center">
                <p class="text-gray-600 font-poppins">Tidak ada data kos yang ditemukan.</p>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

