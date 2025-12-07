@extends('layouts.pemilik')

@section('title', 'Laporan Keuangan - SiKosma')

@section('content')
<div class="min-h-screen bg-gray-100 pb-12">
    <div class="max-w-7xl mx-auto px-6 py-8">
        <h1 class="text-3xl font-bold text-gray-900 font-poppins mb-8">Laporan Keuangan</h1>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Pemesanan -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="text-gray-600 text-sm font-poppins mb-2">Total Pemesanan</div>
                <div class="text-4xl font-bold text-blue-900 font-poppins mb-1">{{ $totalPemesanan }}</div>
                <div class="text-xs text-gray-500 font-poppins">6 tahun terakhir</div>
            </div>

            <!-- Dikonfirmasi -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="text-gray-600 text-sm font-poppins mb-2">Dikonfirmasi</div>
                <div class="text-4xl font-bold text-green-600 font-poppins mb-1">{{ $dikonfirmasi }}</div>
                <div class="text-xs text-gray-500 font-poppins">{{ $persentaseKonfirmasi }}% tingkat konfirmasi</div>
            </div>

            <!-- Selesai -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="text-gray-600 text-sm font-poppins mb-2">Selesai</div>
                <div class="text-4xl font-bold text-yellow-600 font-poppins mb-1">{{ $selesai }}</div>
                <div class="text-xs text-gray-500 font-poppins">{{ $persentasePenyelesaian }}% tingkat penyelesaian</div>
            </div>

            <!-- Total Pendapatan -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="text-gray-600 text-sm font-poppins mb-2">Total Pendapatan</div>
                <div class="text-3xl font-bold text-blue-900 font-poppins mb-1">
                    @php
                        $pendapatanJuta = $totalPendapatan / 1000000;
                        echo 'Rp ' . number_format($pendapatanJuta, 0, ',', '.') . ' Jt';
                    @endphp
                </div>
                <div class="text-xs text-gray-500 font-poppins">6 tahun terakhir</div>
            </div>
        </div>

        <!-- Detail Laporan Bulanan -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900 mb-4 font-poppins">Detail Laporan Bulanan</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Bulan</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Total Pemesanan</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Dikonfirmasi</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Selesai</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($laporanData) > 0)
                            @foreach($laporanData as $data)
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="py-3 px-4 text-sm text-gray-900 font-poppins font-semibold">{{ $data['tahun'] }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $data['pemesanan'] }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $data['dikonfirmasi'] }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $data['selesai'] }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-900 font-poppins font-semibold">
                                        @php
                                            $pendapatanJuta = $data['pendapatan'] / 1000000;
                                            echo 'Rp ' . number_format($pendapatanJuta, 0, ',', '.') . ' Jt';
                                        @endphp
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="py-8 px-4 text-center text-gray-600 font-poppins">
                                    Tidak ada data laporan untuk periode ini.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
