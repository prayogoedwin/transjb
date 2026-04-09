<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('nasabah.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Nasabah') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('View') }}</span>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Detail Nasabah') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Lihat data nasabah') }}</p>
        </div>
        <div class="flex gap-2">
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
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

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('User') }}
                            </label>
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ $nasabah->user?->name ?? '-' }}
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Alamat') }}
                            </label>
                            <div class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">
                                {{ $nasabah->alamat ?? '-' }}
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
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">{{ __('Informasi') }}</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">{{ __('Dibuat') }}</span>
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ $nasabah->created_at->format('d M Y H:i') }}
                            </div>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">{{ __('Diperbarui') }}</span>
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ $nasabah->updated_at->format('d M Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Simpan Pinjam History -->
    @if($nasabah->simpanPinjam->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Riwayat Simpan Pinjam') }}</h3>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Tipe') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Nominal') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Tanggal') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($nasabah->simpanPinjam as $item)
                            <tr>
                                <td class="px-6 py-3">
                                    @if($item->tipe === 'simpan')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Simpan</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Pinjam</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
                                    Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-3 text-gray-900 dark:text-gray-100">
                                    {{ $item->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-layouts.app>
