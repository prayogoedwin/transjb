<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <x-icons.chevron-right />
        <a href="{{ route('simpan_pinjam.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Bayar & Hutang') }}</a>
        <x-icons.chevron-right />
        <span class="text-gray-500 dark:text-gray-400">{{ __('Lihat') }}</span>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Detail Bayar & Hutang') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Lihat data Bayar & Hutang') }}</p>
        </div>
        <div class="flex gap-2">
            <!-- @if(auth()->user()->hasPermission('edit-simpan-pinjam'))
                <a href="{{ route('simpan_pinjam.edit', $simpanPinjam) }}">
                    <x-button type="primary">{{ __('Edit') }}</x-button>
                </a>
            @endif -->
            <a href="{{ route('simpan_pinjam.index') }}">
                <x-button type="secondary">{{ __('Kembali') }}</x-button>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Bayar & Hutang Detail -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <div class="max-w-2xl">
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Nasabah') }}
                            </label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $simpanPinjam->nasabah->nama }}
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Tipe') }}
                            </label>
                            <div>
                                @if($simpanPinjam->tipe === 'bayar')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">{{ __('Bayar') }}</span>
                                @elseif($simpanPinjam->tipe === 'hutang')
                                     <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">{{ __('Hutang') }}</span>
                                @elseif($simpanPinjam->tipe === 'transaksi')
                                     <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">{{ __('Transaksi') }}</span>
                                @elseif($simpanPinjam->tipe === 'ambil')
                                     <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark    :text-yellow-200">{{ __('Ambil') }}</span>
                                @else
                                     <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">{{ ucfirst($simpanPinjam->tipe) }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Nominal') }}
                            </label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Rp {{ number_format($simpanPinjam->nominal, 0, ',', '.') }}
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
                                {{ $simpanPinjam->created_at->format('d M Y H:i') }}
                            </div>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">{{ __('Diperbarui') }}</span>
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ $simpanPinjam->updated_at->format('d M Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
