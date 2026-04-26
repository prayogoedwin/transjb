<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <x-icons.chevron-right />
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

    <!-- Rekap Bisnis -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Rekap Bisnis Keseluruhan') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-900/30 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">{{ __('Indikator') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">{{ __('Nilai') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-3 text-sm text-gray-900 dark:text-gray-100">{{ __('Total Nasabah') }}</td>
                        <td class="px-6 py-3 text-sm text-right font-semibold text-gray-900 dark:text-gray-100">{{ $stats['total_nasabah'] }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-3 text-sm text-gray-900 dark:text-gray-100">{{ __('Total Produk') }}</td>
                        <td class="px-6 py-3 text-sm text-right font-semibold text-gray-900 dark:text-gray-100">{{ $stats['total_produk'] }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-3 text-sm text-gray-900 dark:text-gray-100">{{ __('Total Sisa Hutang') }}</td>
                        <td class="px-6 py-3 text-sm text-right font-semibold text-red-600 dark:text-red-400">{{ formatCurrencyRound($stats['total_sisa_hutang']) }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-3 text-sm text-gray-900 dark:text-gray-100">{{ __('Total Sisa Simpanan') }}</td>
                        <td class="px-6 py-3 text-sm text-right font-semibold text-green-600 dark:text-green-400">{{ formatCurrencyRound($stats['total_sisa_simpanan']) }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-3 text-sm text-gray-900 dark:text-gray-100">{{ __('Total Pembelian') }}</td>
                        <td class="px-6 py-3 text-sm text-right font-semibold text-gray-900 dark:text-gray-100">{{ formatCurrencyRound($stats['total_pembelian']) }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-3 text-sm text-gray-900 dark:text-gray-100">{{ __('Total Penjualan') }}</td>
                        <td class="px-6 py-3 text-sm text-right font-semibold text-blue-600 dark:text-blue-400">{{ formatCurrencyRound($stats['total_penjualan']) }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-3 text-sm text-gray-900 dark:text-gray-100">{{ __('Jumlah Transaksi Pembelian') }}</td>
                        <td class="px-6 py-3 text-sm text-right font-semibold text-gray-900 dark:text-gray-100">{{ $stats['total_transaksi_pembelian'] }}x</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-3 text-sm text-gray-900 dark:text-gray-100">{{ __('Jumlah Transaksi Penjualan') }}</td>
                        <td class="px-6 py-3 text-sm text-right font-semibold text-gray-900 dark:text-gray-100">{{ $stats['total_transaksi_penjualan'] }}x</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Stok Per Produk -->
    <div class="mt-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Total Stok Per Produk') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-900/30 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">{{ __('Produk') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">{{ __('Total Stok') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">{{ __('Satuan') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($stokPerProduk as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $item->product?->nama_produk ?? '-' }}</td>
                                <td class="px-6 py-3 text-sm text-right font-semibold text-gray-900 dark:text-gray-100">{{ formatDecimalSmart($item->total_stok) }}</td>
                                <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $item->product?->satuan ?? '-' }}</td>
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

    <!-- Detail Transaksi -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        <!-- Detail Pembelian -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Detail Pembelian') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-900/30 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">{{ __('Tanggal') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">{{ __('Nasabah') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">{{ __('Nominal') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($pembelianDetail->take(10) as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $item->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $item->nasabah?->nama ?? '-' }}</td>
                                <td class="px-6 py-3 text-sm text-right font-semibold text-gray-900 dark:text-gray-100">{{ formatCurrencyRound($item->harga_akhir) }}</td>
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

        <!-- Detail Penjualan -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Detail Penjualan') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-900/30 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">{{ __('Tanggal') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">{{ __('Pelanggan') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">{{ __('Nominal') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($penjualanDetail->take(10) as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $item->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $item->nama_customer ?? '-' }}</td>
                                <td class="px-6 py-3 text-sm text-right font-semibold text-gray-900 dark:text-gray-100">{{ formatCurrencyRound($item->total_pembelian) }}</td>
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
