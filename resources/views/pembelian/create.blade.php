<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('products.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Products') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Create') }}</span>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Create Product') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Add a new product to inventory') }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <form action="{{ route('products.store') }}" method="POST" class="max-w-2xl">
                @csrf

                <div class="mb-4">
                    <x-forms.input label="Nama Produk" name="nama_produk" type="text" value="{{ old('nama_produk') }}" required />
                </div>

                <div class="mb-4">
                    <x-forms.input label="Satuan" name="satuan" type="text" value="{{ old('satuan', 'kg') }}" required />
                </div>

                <div class="mb-6">
                    <x-forms.input label="Harga Beli" name="harga_beli" type="number" step="0.01" min="0" value="{{ old('harga_beli', 0) }}" required />
                </div>

                <div class="flex gap-3">
                    <x-button type="primary">{{ __('Create') }}</x-button>
                    <a href="{{ route('products.index') }}">
                        <x-button type="secondary">{{ __('Cancel') }}</x-button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
