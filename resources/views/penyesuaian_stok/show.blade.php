<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('penyesuaian_stok.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Penyesuaian Stok') }}</a>
         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Detail') }}</span>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Detail Penyesuaian Stok') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Informasi lengkap penyesuaian stok') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('penyesuaian_stok.index') }}">
                <x-button type="secondary">{{ __('Kembali') }}</x-button>
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden max-w-2xl">
        <div class="p-6">
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Produk') }}</label>
                <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{ $penyesuaianStok->product->nama_produk ?? '-' }}
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Status') }}</label>
                <div>
                    @if($penyesuaianStok->status === 'in')
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            Masuk (Tambah Stok)
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                            Keluar (Kurangi Stok)
                        </span>
                    @endif
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Jumlah') }}</label>
                <div class="text-gray-900 dark:text-gray-100">
                    {{ formatDecimalSmart($penyesuaianStok->jumlah) }} {{ $penyesuaianStok->satuan }}
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Keterangan') }}</label>
                <div class="text-gray-900 dark:text-gray-100">
                    {{ $penyesuaianStok->keterangan ?: '-' }}
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Dibuat Oleh') }}</label>
                <div class="text-gray-900 dark:text-gray-100">
                    {{ $penyesuaianStok->user->name ?? '-' }}
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Tanggal') }}</label>
                <div class="text-gray-900 dark:text-gray-100">
                    {{ $penyesuaianStok->created_at_id }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
