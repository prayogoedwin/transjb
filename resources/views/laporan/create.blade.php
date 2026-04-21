<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <x-icons.chevron-right />
        <a href="{{ route('products.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Products') }}</a>
        <x-icons.chevron-right />
        <span class="text-gray-500 dark:text-gray-400">{{ __('Create') }}</span>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Create Product') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Add a new product to inventory') }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <form action="{{ route('products.store') }}" method="POST" class="max-w-2xl" @submit="formSubmitted = true">
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
                    <x-button type="primary" x-bind:disabled="formSubmitted" x-text="formSubmitted ? '{{ __('Saving...') }}' : '{{ __('Create') }}'"></x-button>
                    <a href="{{ route('products.index') }}">
                        <x-button type="secondary">{{ __('Cancel') }}</x-button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
