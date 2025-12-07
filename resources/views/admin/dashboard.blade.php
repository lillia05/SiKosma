@extends('layouts.admin')

@section('title', 'Beranda Admin - SiKosma')

@section('content')
<div class="min-h-screen bg-gray-100 pb-12">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 font-poppins flex items-center gap-2">
        {{-- Heroicons: home (solid) - https://heroicons.com/ --}}
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8 text-[#0A3B65]">
            <path d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />
            <path d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />
        </svg>
            BERANDA
        </h1>

        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="text-gray-600 text-sm font-poppins mb-2">Total Kos Terdaftar</div>
                <div class="text-4xl font-bold text-blue-900 font-poppins mb-1">{{ $totalKos }}</div>
                <div class="text-xs text-gray-500 font-poppins">Nama Kos Tipe Kamar</div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="text-gray-600 text-sm font-poppins mb-2">Total Pengguna</div>
                <div class="text-4xl font-bold text-blue-900 font-poppins mb-1">{{ $totalUsers }}</div>
                <div class="text-xs text-gray-500 font-poppins">Pengguna terdaftar</div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="text-gray-600 text-sm font-poppins mb-2">Total Transaksi Bulan Ini</div>
                <div class="text-3xl font-bold text-blue-900 font-poppins mb-1">Rp {{ number_format($totalTransaksiBulanIni, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Notifikasi dan Grafik -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Notifikasi Terbaru -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4 font-poppins">Notifikasi Terbaru</h2>
                <div class="space-y-4">
                    @forelse($notifications as $notification)
                    <div class="flex items-start justify-between {{ !$loop->last ? 'pb-4 border-b' : '' }} {{ isset($notification['sudah_dibaca']) && $notification['sudah_dibaca'] ? 'opacity-75' : '' }}">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                @if(isset($notification['sudah_dibaca']) && !$notification['sudah_dibaca'] && $notification['status'] === 'MENUNGGU')
                                    <span class="w-2 h-2 bg-blue-600 rounded-full"></span>
                                @endif
                                <p class="text-sm text-gray-900 font-poppins font-semibold">
                                    {{ $notification['judul'] }}
                                    @if(isset($notification['user']) && $notification['user'])
                                        : [{{ $notification['user']->nama }}]
                                    @endif
                                </p>
                            </div>
                            @if(isset($notification['pesan']) && $notification['pesan'])
                                <p class="text-xs text-gray-600 font-poppins mt-1">
                                    {{ $notification['pesan'] }}
                                </p>
                            @endif
                            <p class="text-xs text-gray-500 font-poppins mt-1">
                                {{ $notification['created_at']->setTimezone('Asia/Jakarta')->format('Y-m-d H:i') }}
                            </p>
                        </div>
                        <span class="{{ $notification['status_class'] }} text-xs font-semibold px-3 py-1 rounded-full font-poppins ml-2">
                            {{ $notification['status'] }}
                        </span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 font-poppins text-center py-4">Tidak ada notifikasi</p>
                    @endforelse
                </div>
            </div>

            <!-- Grafik Pemesanan -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4 font-poppins">Grafik Pemesanan</h2>
                <div style="width: 100%; height: 250px;">
                    <canvas id="bookingChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Daftar Aktivitas Sistem -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900 mb-4 font-poppins">Daftar Aktivitas Sistem</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">ID</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Nama Kos</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Kamar</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Tgl Mulai</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $activity['id'] }}</td>
                            <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $activity['kos_name'] }}</td>
                            <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $activity['room'] }}</td>
                            <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $activity['date'] }}</td>
                            <td class="py-3 px-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold font-poppins 
                                    @if($activity['status'] === 'MENUNGGU')
                                        bg-yellow-100 text-yellow-800
                                    @elseif($activity['status'] === 'DISETUJUI')
                                        bg-green-100 text-green-800
                                    @elseif($activity['status'] === 'DITOLAK')
                                        bg-red-100 text-red-800
                                    @else
                                        bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $activity['status'] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500 font-poppins">Tidak ada aktivitas</td>
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
    // Chart.js Configuration
    document.addEventListener('DOMContentLoaded', function() {
        const chartData = @json($chartData);
        
        const ctx = document.getElementById('bookingChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.map(item => item.year),
                    datasets: [{
                        label: 'Pemesanan',
                        data: chartData.map(item => item.orders),
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
                                color: '#9ca3af'
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