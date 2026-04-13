<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('pembelian.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Pembelian') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Lihat') }}</span>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Detail Pembelian') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Lihat data pembelian') }}</p>
        </div>
        <div class="flex gap-2">
            @if(auth()->user()->hasPermission('edit-pembelian'))
                <a href="{{ route('pembelian.edit', $pembelian) }}">
                    <x-button type="primary">{{ __('Edit') }}</x-button>
                </a>
            @endif
            <a href="{{ route('pembelian.index') }}">
                <x-button type="secondary">{{ __('Kembali') }}</x-button>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Pembelian Detail -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <div class="max-w-2xl">
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Nasabah') }}
                            </label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                <a href="{{ route('nasabah.show', $pembelian->nasabah) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $pembelian->nasabah?->nama ?? '-' }}
                                </a>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Produk') }}
                            </label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $pembelian->product->nama_produk }}
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Harga Satuan Beli') }}
                            </label>
                            <div class="text-gray-900 dark:text-gray-100">
                                Rp {{ number_format($pembelian->harga_satuan_beli, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Satuan') }}
                            </label>
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ $pembelian->satuan }}
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Total Berat') }}
                            </label>
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ number_format($pembelian->total_berat, 2) }}
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Total Harga') }}
                            </label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Biaya Admin (%)') }}
                            </label>
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ number_format($pembelian->biaya_admin_persen, 2) }}%
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Biaya Admin (Rp)') }}
                            </label>
                            <div class="text-gray-900 dark:text-gray-100">
                                Rp {{ number_format($pembelian->biaya_admin, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Harga Akhir') }}
                            </label>
                            <div class="text-xl font-bold text-blue-600 dark:text-blue-400">
                                Rp {{ number_format($pembelian->harga_akhir, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Informasi') }}</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">{{ __('Dibuat') }}</span>
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ $pembelian->created_at->format('d M Y H:i') }}
                            </div>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">{{ __('Diperbarui') }}</span>
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ $pembelian->updated_at->format('d M Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
