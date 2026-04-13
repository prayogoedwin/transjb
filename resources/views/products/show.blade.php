<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('products.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Produk') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('View') }}</span>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Detail Produk') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Detail informasi produk dan stok') }}</p>
        </div>
        <div class="flex gap-2">
            @if(auth()->user()->hasPermission('edit-products'))
                <a href="{{ route('products.edit', $product) }}">
                    <x-button type="primary">{{ __('Edit Produk') }}</x-button>
                </a>
            @endif
            <a href="{{ route('products.index') }}">
                <x-button type="secondary">{{ __('Kembali') }}</x-button>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Product Info -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <div class="max-w-2xl">
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Nama Produk') }}
                            </label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $product->nama_produk }}
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Satuan') }}
                            </label>
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ $product->satuan }}
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Harga Beli') }}
                            </label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ formatCurrency($product->harga_beli, 0) }}
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Tanggal Update Harga') }}
                            </label>
                            <div class="text-gray-900 dark:text-gray-100">
                                @if($product->tanggal_update_harga_beli)
                                    {{ $product->tanggal_update_harga_beli->format('d F Y H:i') }}
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">-</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Tanggal Dibuat') }}
                            </label>
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ $product->created_at->format('d F Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Summary -->
        <div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Ringkasan Stok') }}</h3>
                    
                    <div class="mb-4">
                        <label class="block text-xs text-right font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                            {{ __('Stok Masuk') }}
                        </label>
                        <div class="text-2xl font-bold text-right text-green-600 dark:text-green-400">
                            {{ formatDecimal($inStock) }}  <span class="text-xs text-gray-500 dark:text-gray-400">{{ $product->satuan }}</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs text-right font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                            {{ __('Stok Keluar') }}
                        </label>
                        <div class="text-2xl font-bold text-right text-red-600 dark:text-red-400">
                            {{ formatDecimal($outStock, 2) }}  <span class="text-xs text-gray-500 dark:text-gray-400">{{ $product->satuan }}</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-500 dark:border-gray-600">
                        <label class="block text-xs text-right font-medium text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-1">
                            {{ __('Stok Total') }}
                        </label>
                        <div class="text-3xl font-bold text-right text-blue-600 dark:text-blue-400">
                            {{ formatDecimal($totalStock, 2) }}  <span class="text-xs text-gray-500 dark:text-gray-400">{{ $product->satuan }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Price History -->
    @if($product->priceHistories->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Riwayat Harga') }}</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Harga Sebelumnya') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Diperbarui Oleh') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Tanggal') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($product->priceHistories as $history)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900 text-right dark:text-gray-100">
                                        {{ formatCurrency($history->harga_beli_before, 0) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $history->createdBy->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $history->created_at->format('d M Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Stock Transactions -->
    @if($product->stocks->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Riwayat Stok') }}</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Type') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Quantity') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Nasabah') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($product->stocks as $stock)
                                <tr>
                                    <td class="px-6 py-4 text-sm">
                                        @if($stock->transaksi === 'in')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                {{ __('IN') }}
                                            </span>
                                        @elseif($stock->transaksi === 'out')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                {{ __('OUT') }}
                                            </span>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">{{ __('-') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm text-gray-900 dark:text-gray-100">
                                        {{ formatDecimal($stock->jumlah) }} {{ $product->satuan }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $stock->pembelian->nasabah->nama ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $stock->created_at->format('d M Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
    </div>
</x-layouts.app>
