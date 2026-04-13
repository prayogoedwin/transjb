<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('penjualan.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Penjualan') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Lihat') }}</span>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Detail Penjualan') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Lihat data penjualan') }}</p>
        </div>
        <div class="flex gap-2">
            @if(auth()->user()->hasPermission('edit-penjualan'))
                <a href="{{ route('penjualan.edit', $penjualan) }}">
                    <x-button type="primary">{{ __('Edit') }}</x-button>
                </a>
            @endif
            <a href="{{ route('penjualan.printInvoice', $penjualan) }}" target="_blank">
                <x-button type="secondary">{{ __('Cetak') }}</x-button>
            </a>
            <a href="{{ route('penjualan.index') }}">
                <x-button type="secondary">{{ __('Kembali') }}</x-button>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Penjualan Detail -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <div class="max-w-2xl">
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Pembeli') }}
                            </label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $penjualan->nama_customer ?? '-' }}
                            </div>
                        </div>

                        @if($penjualan->nopol)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Nopol') }}
                            </label>
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ $penjualan->nopol }}
                            </div>
                        </div>
                        @endif

                        @if($penjualan->keterangan)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Keterangan') }}
                            </label>
                            <div class="text-gray-900 dark:text-gray-100 text-sm">
                                {{ $penjualan->keterangan }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden h-fit">
            <div class="p-6">
                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">{{ __('Ringkasan') }}</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ __('ID') }}</p>
                        <p class="text-gray-900 dark:text-white font-medium">#{{ $penjualan->id }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ __('Tanggal') }}</p>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $penjualan->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ __('Total Item') }}</p>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $penjualan->details->count() }}</p>
                    </div>
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ __('Total Penjualan') }}</p>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ formatCurrencyRound($penjualan->total_pembelian) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Item Penjualan') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Produk') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Harga') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Qty') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Satuan') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Total') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($penjualan->details as $detail)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $detail->nama_produk }}</td>
                        <td class="px-6 py-4 text-sm text-right text-gray-900 dark:text-white font-medium">{{ formatCurrencyRound($detail->harga_satuan) }}</td>
                        <td class="px-6 py-4 text-sm text-right text-gray-900 dark:text-white font-medium">{{ formatDecimalSmart($detail->jumlah) }}</td>
                        <td class="px-6 py-4 text-sm text-center text-gray-900 dark:text-white">{{ $detail->satuan }}</td>
                        <td class="px-6 py-4 text-sm text-right font-bold text-blue-600 dark:text-blue-400">{{ formatCurrencyRound($detail->harga_total) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">{{ __('Tidak ada item') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4">
            <div class="flex justify-end">
                <div class="w-full md:w-1/3">
                    <div class="flex justify-between mb-2 text-sm">
                        <span class="text-gray-700 dark:text-gray-300">{{ __('Total Item:') }}</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $penjualan->details->count() }}</span>
                    </div>
                    <div class="flex justify-between mb-4 pb-4 border-b border-gray-200 dark:border-gray-600 text-sm">
                        <span class="text-gray-700 dark:text-gray-300">{{ __('Total Qty:') }}</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ formatDecimalSmart($penjualan->details->sum('jumlah')) }}</span>
                    </div>
                    <div class="flex justify-between text-lg">
                        <span class="font-semibold text-gray-900 dark:text-white">{{ __('Total Penjualan:') }}</span>
                        <span class="font-bold text-blue-600 dark:text-blue-400">{{ formatCurrencyRound($penjualan->total_pembelian) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
