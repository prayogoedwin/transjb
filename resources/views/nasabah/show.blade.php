<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <x-icons.chevron-right />
        <a href="{{ route('nasabah.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Nasabah') }}</a>
        <x-icons.chevron-right />
        <span class="text-gray-500 dark:text-gray-400">{{ __('Detail') }}</span>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Detail Nasabah') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Lihat data nasabah') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('nasabah.export-pdf', $nasabah) }}" target="_blank">
                <x-button type="secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"></path>
                    </svg>
                    {{ __('Cetak PDF') }}
                </x-button>
            </a>
            @if(auth()->user()->hasPermission('edit-nasabah'))
                <a href="{{ route('nasabah.edit', $nasabah) }}">
                    <x-button type="primary">{{ __('Edit') }}</x-button>
                </a>
            @endif
            <a href="{{ route('nasabah.index') }}">
                <x-button type="secondary">{{ __('Kembali') }}</x-button>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
        <!-- Nasabah Info -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <div class="max-w-2xl">
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Nama') }}
                            </label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $nasabah->nama }}
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('No. Telepon') }}
                            </label>
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ $nasabah->no_telp ?? '-' }}
                            </div>
                        </div>
<!-- 
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('User') }}
                            </label>
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ $nasabah->user?->name ?? '-' }}
                            </div>
                        </div> -->

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Alamat') }}
                            </label>
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ $nasabah->alamat ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">{{ __('Riwayat Bayar & Hutang') }}</h3>
                    <div class="space-y-3 text-sm">
                        <!-- <div>
                            <div class="text-gray-600 dark:text-gray-400">{{ __('Total Transaksi') }}</div>
                            <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $nasabah->simpanPinjam->count() }}</div>
                        </div> -->
                        @php
                            $totalBayar = $nasabah->simpanPinjam->where('tipe', 'bayar')->sum('nominal');
                            $totalHutang = $nasabah->simpanPinjam->where('tipe', 'hutang')->sum('nominal');
                            $totalTransaksi = $nasabah->simpanPinjam->where('tipe', 'transaksi')->sum('nominal');
                            $totalAmbil = $nasabah->simpanPinjam->where('tipe', 'ambil')->sum('nominal');

                            $sisaHutang = max(0, $totalHutang - $totalBayar);
                            $sisaBayar = max(0, $totalBayar - $totalHutang);
                        @endphp
                        <div>
                            <div class="text-gray-600 dark:text-gray-400">{{ __('Total Bayar') }}</div>
                            <div class="text-lg font-semibold text-green-600 dark:text-green-400">{{ formatCurrencyRound($totalBayar) }}</div>
                        </div>
                        <div>
                            <div class="text-gray-600 dark:text-gray-400">{{ __('Total Hutang') }}</div>
                            <div class="text-lg font-semibold text-red-600 dark:text-red-400">{{ formatCurrencyRound($totalHutang) }}</div>
                        </div>
                        <div>
                            <div class="text-gray-600 dark:text-gray-400">{{ __('Total Transaksi') }}</div>
                            <div class="text-lg font-semibold text-green-600 dark:text-green-400">{{ $totalTransaksi > 0 ? formatCurrencyRound($totalTransaksi) : '0' }}</div>
                        </div>
                        <div>
                            <div class="text-gray-600 dark:text-gray-400">{{ __('Total Ambil') }}</div>
                            <div class="text-lg font-semibold text-yellow-600 dark:text-yellow-400">{{ $totalAmbil > 0 ? formatCurrencyRound($totalAmbil) : '0' }}</div>                        
                        </div>
                        <div class="border-t border-gray-100 dark:border-gray-700 pt-3">
                            <div class="text-gray-600 dark:text-gray-400">{{ __('Sisa Hutang') }}</div>
                            <div class="text-lg font-semibold text-red-600 dark:text-red-400">{{ $sisaHutang > 0 ? formatCurrencyRound($sisaHutang) : '0' }}</div>
                        </div>
                        <div>
                            <div class="text-gray-600 dark:text-gray-400">{{ __('Sisa Bayar') }}</div>
                            <div class="text-lg font-semibold text-green-600 dark:text-green-400">{{ $sisaBayar > 0 ? formatCurrencyRound($sisaBayar) : '0' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <!-- Bayar & Hutang History -->
    @if($nasabah->simpanPinjam->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Detail Riwayat Bayar & Hutang') }}</h3>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Tipe') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Nominal') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Tanggal') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($nasabah->simpanPinjam->sortByDesc('created_at') as $item)
                            <tr>
                                <td class="px-6 py-3">
                                    @if($item->tipe === 'bayar')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">{{ __('Bayar') }}</span>
                                    @elseif($item->tipe === 'hutang')
                                         <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">{{ __('Hutang') }}</span>
                                    @elseif($item->tipe === 'transaksi')
                                         <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">{{ __('Transaksi') }}</span>
                                    @elseif($item->tipe === 'ambil')
                                         <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark    :text-yellow-200">{{ __('Ambil') }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">{{ __('Lainnya') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
                                    {{ formatCurrencyRound($item->nominal) }}
                                </td>
                                <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
                                    {{ $item->created_at->format('d M Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                {{ __('Belum ada riwayat Bayar & Hutang') }}
            </div>
        </div>
    @endif



    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
         <!-- Info Card -->
        <div class="mt-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">{{ __('Detail Penjualan') }}</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <div class="text-gray-600 dark:text-gray-400">{{ __('Total Transaksi') }}</div>
                            <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $nasabah->pembelian->count() }}</div>
                        </div>
                        <div>
                            <div class="text-gray-600 dark:text-gray-400">{{ __('Total Berat') }}</div>
                            <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ formatDecimalSmart($nasabah->pembelian->sum('total_berat')) }} kg</div>
                        </div>
                        <div>
                            <div class="text-gray-600 dark:text-gray-400">{{ __('Total Harga') }}</div>
                            <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ formatCurrencyRound($nasabah->pembelian->sum('harga_akhir')) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Pembelian History -->
    @if($nasabah->pembelian->count() > 0)
        <div class="col-span-1 lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mt-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Riwayat Penjualan') }}</h3>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Produk') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Jumlah') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Harga Satuan') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Total') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Tanggal') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($nasabah->pembelian->sortByDesc('created_at') as $item)
                            <tr>
                                <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
                                    {{ $item->product->nama_produk ?? '-' }}
                                </td>
                                <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
                                    {{ formatDecimalSmart($item->total_berat) }} {{ $item->product->satuan ?? 'kg' }}
                                </td>
                                <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
                                    {{ formatCurrencyRound($item->harga_satuan_beli) }}
                                </td>
                                <td class="px-6 py-3 text-gray-900 dark:text-gray-100 font-semibold">
                                    {{ formatCurrencyRound($item->harga_akhir) }}
                                </td>
                                <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
                                    {{ $item->created_at->format('d M Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mt-6">
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                {{ __('Belum ada riwayat penjualan') }}
            </div>
        </div>
    @endif
    </div>
</x-layouts.app>
