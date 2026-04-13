<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Laporan & Rekap') }}</span>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Laporan Bisnis') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Ringkasan kegiatan bisnis dari') }} {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} {{ __('hingga') }} {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</p>
        </div>
        <div class="flex gap-2">
            <form method="GET" action="{{ route('laporan.index') }}" class="flex gap-2 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Dari') }}</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Hingga') }}</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                </div>
                <button type="submit" class="px-4 mt-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">{{ __('Filter') }}</button>
            </form>
            <a href="{{ route('laporan.print', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}" target="_blank" class="mt-6">
                <x-button type="primary" class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4" />
                    </svg>
                    {{ __('Print') }}
                </x-button>
            </a>
            <!-- <a href="{{ route('laporan.export-pdf', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}">
                <x-button type="success" class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    {{ __('Export PDF') }}
                </x-button>
            </a> -->
        </div>
    </div>

    <!-- Key Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Nasabah -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">{{ __('Total Nasabah') }}</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $stats['total_nasabah'] }}</p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Produk -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">{{ __('Total Produk') }}</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $stats['total_produk'] }}</p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8 4m-8-4v10l8 4m0-10l-8-4" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Penjualan -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">{{ __('Total Penjualan') }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ formatCurrencyRound($stats['total_penjualan']) }}</p>
                    </div>
                    <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Simpan Pinjam -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">{{ __('Selisih Simpan-Pinjam') }}</p>
                        <p class="text-2xl font-bold {{ $stats['total_simpan'] - $stats['total_pinjam'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} mt-2">{{ formatCurrencyRound($stats['total_simpan'] - $stats['total_pinjam']) }}</p>
                    </div>
                    <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 mt-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Penjualan') }}</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('Transaksi') }}</span>
                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $stats['total_pembelian'] }} x</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('Total Penjualan') }}</span>
                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ formatCurrencyRound($stats['total_penjualan']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('Biaya Admin') }}</span>
                    <span class="font-semibold text-red-600 dark:text-red-400">{{ formatCurrencyRound($stats['total_biaya_admin']) }}</span>
                </div>
                <!-- <div class="border-t border-gray-200 dark:border-gray-700 pt-3 mt-3 flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('Pendapatan Bersih') }}</span>
                    <span class="font-semibold text-green-600 dark:text-green-400">{{ formatCurrencyRound($stats['total_penjualan'] - $stats['total_biaya_admin']) }}</span>
                </div> -->
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Simpan Pinjam') }}</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('Total Simpan') }}</span>
                    <span class="font-semibold text-green-600 dark:text-green-400">{{ formatCurrencyRound($stats['total_simpan']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('Total Pinjam') }}</span>
                    <span class="font-semibold text-red-600 dark:text-red-400">{{ formatCurrencyRound($stats['total_pinjam']) }}</span>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700 pt-3 mt-3 flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('Selisih') }}</span>
                    <span class="font-semibold {{ $stats['total_simpan'] - $stats['total_pinjam'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">{{ formatCurrencyRound($stats['total_simpan'] - $stats['total_pinjam']) }}</span>
                </div>
            </div>
        </div>

        <!-- <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Performa') }}</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('Rata-rata Penjualan') }}</span>
                    <span class="font-semibold text-gray-900 dark:text-gray-100">
                        @php
                            $days = \Carbon\Carbon::parse($dateTo)->diffInDays(\Carbon\Carbon::parse($dateFrom)) + 1;
                            $avgPenjualan = $days > 0 ? $stats['total_penjualan'] / $days : 0;
                        @endphp
                        {{ formatCurrencyRound($avgPenjualan) }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('Bilangan Hari') }}</span>
                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $days }} hari</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('Rata-rata per Transaksi') }}</span>
                    <span class="font-semibold text-gray-900 dark:text-gray-100">
                        @php
                            $avgTransaksi = $stats['total_pembelian'] > 0 ? $stats['total_penjualan'] / $stats['total_pembelian'] : 0;
                        @endphp
                        {{ formatCurrencyRound($avgTransaksi) }}
                    </span>
                </div>
            </div>
        </div> -->
    </div>

    <!-- Detail Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        <!-- Top Products -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Produk Terlaris') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @php
                            $topProducts = \App\Models\Pembelian::with('product')
                                ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
                                ->get()
                                ->groupBy('product.nama_produk')
                                ->map(function($items) {
                                    return [
                                        'nama' => $items->first()->product->nama_produk,
                                        'total_berat' => $items->sum('total_berat'),
                                        'total_nilai' => $items->sum('harga_akhir'),
                                    ];
                                })
                                ->sortByDesc('total_nilai')
                                ->take(5);
                        @endphp
                        @forelse($topProducts as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $item['nama'] }}</td>
                                <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400">{{ formatDecimalSmart($item['total_berat']) }} kg</td>
                                <td class="px-6 py-3 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ formatCurrencyRound($item['total_nilai']) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">{{ __('Tidak ada data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Customers -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Nasabah Terbaik') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @php
                            $topCustomers = \App\Models\Pembelian::with('nasabah')
                                ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
                                ->get()
                                ->groupBy('nasabah.nama')
                                ->map(function($items) {
                                    return [
                                        'nama' => $items->first()->nasabah?->nama ?? 'Unknown',
                                        'jumlah' => $items->count(),
                                        'total' => $items->sum('harga_akhir'),
                                    ];
                                })
                                ->sortByDesc('total')
                                ->take(5);
                        @endphp
                        @forelse($topCustomers as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $item['nama'] }}</td>
                                <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $item['jumlah'] }} x</td>
                                <td class="px-6 py-3 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ formatCurrencyRound($item['total']) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">{{ __('Tidak ada data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
