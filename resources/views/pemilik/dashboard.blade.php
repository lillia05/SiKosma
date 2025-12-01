@extends('layouts.pemilik')

@section('title', 'Beranda Pemilik Kos - SiKosma')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto px-6 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 font-poppins">Beranda Pemilik Kos</h1>

        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Kos Aktif -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="text-gray-600 text-sm font-poppins mb-2">Total Kos Aktif</div>
                <div class="text-4xl font-bold text-blue-900 font-poppins mb-1">{{ $totalKosAktif }}</div>
                <div class="text-xs text-gray-500 font-poppins">Nama Kos Tipe Kamar</div>
            </div>

            <!-- Kamar Terisi -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="text-gray-600 text-sm font-poppins mb-3">Kamar Terisi</div>
                <div class="flex items-baseline gap-2 mb-3">
                    <span class="text-4xl font-bold text-blue-900 font-poppins">{{ $occupiedRooms }}</span>
                    <span class="text-gray-500 font-poppins text-sm">/ {{ $totalRooms }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-900 h-2 rounded-full" style="width: {{ $occupancyPercentage }}%"></div>
                </div>
                <div class="text-xs text-gray-500 mt-2 font-poppins">Kamar Terisi Tipe Kamar</div>
            </div>

            <!-- Pendapatan Bulan Ini -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="text-gray-600 text-sm font-poppins mb-2">Pendapatan Bulan Ini</div>
                <div class="text-3xl font-bold text-blue-900 font-poppins mb-1">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</div>
                <div class="text-xs text-gray-500 font-poppins">Pendapatan Bulan Ini {{ now()->format('M Y') }}</div>
            </div>
        </div>

        <!-- Notifications and Graph Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Notifikasi Terbaru -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4 font-poppins">Notifikasi Terbaru</h2>
                <div class="space-y-3">
                    @forelse($notifications as $notif)
                    <div class="flex items-start justify-between pb-3 {{ !$loop->last ? 'border-b' : '' }}">
                        <div class="flex-1">
                            <p class="text-sm text-gray-700 font-poppins">{{ $notif['text'] }}</p>
                            @if(isset($notif['date']))
                                <p class="text-xs text-gray-500 mt-1 font-poppins">{{ $notif['date'] }}</p>
                            @endif
                        </div>
                        @if(isset($notif['status']) && $notif['status'])
                            <span class="bg-green-200 text-green-800 text-xs px-2 py-1 rounded font-poppins whitespace-nowrap ml-2">
                                {{ $notif['status'] }}
                            </span>
                        @endif
                        @if(isset($notif['type']) && $notif['type'] === 'review')
                            <span class="text-yellow-400 text-lg">⭐</span>
                        @endif
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 font-poppins text-center py-4">Tidak ada notifikasi</p>
                    @endforelse
                </div>
            </div>

            <!-- Grafik Ketersediaan Kamar -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4 font-poppins">Grafik Ketersediaan Kamar</h2>
                <div style="width: 100%; height: 250px;">
                    <canvas id="roomAvailabilityChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Daftar Pemesanan Terbaru -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900 mb-4 font-poppins">Daftar Pemesanan Terbaru</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">ID Pesanan</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Nama Kos</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Kamar</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Tgl Mulai</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings as $booking)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $booking['id'] }}</td>
                            <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $booking['kosName'] }}</td>
                            <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $booking['room'] }}</td>
                            <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $booking['startDate'] }}</td>
                            <td class="py-3 px-4 text-sm">
                                <span class="px-3 py-1 rounded text-xs font-poppins 
                                    @if($booking['status'] === 'MENUNGGU')
                                        bg-yellow-200 text-yellow-800
                                    @elseif($booking['status'] === 'TERKONFIRMASI')
                                        bg-green-200 text-green-800
                                    @elseif($booking['status'] === 'SELESAI')
                                        bg-blue-200 text-blue-800
                                    @else
                                        bg-gray-200 text-gray-800
                                    @endif">
                                    {{ $booking['status'] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500 font-poppins">Tidak ada pemesanan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Chart.js Configuration for Room Availability
    document.addEventListener('DOMContentLoaded', function() {
        const chartData = @json($chartData);
        
        const ctx = document.getElementById('roomAvailabilityChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.map(item => item.month),
                    datasets: [{
                        label: 'Kamar',
                        data: chartData.map(item => item.kamar),
                        backgroundColor: '#1e3a5f',
                        borderRadius: [8, 8, 0, 0],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: {
                                family: 'Poppins',
                                size: 12
                            },
                            bodyFont: {
                                family: 'Poppins',
                                size: 12
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: {
                                    family: 'Poppins',
                                    size: 11
                                },
                                color: '#9ca3af',
                                stepSize: 8
                            },
                            grid: {
                                color: '#e5e7eb'
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    family: 'Poppins',
                                    size: 11
                                },
                                color: '#9ca3af'
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection

