<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <x-icons.chevron-right />
        <a href="{{ route('products.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Produk') }}</a>
        <x-icons.chevron-right />
        <span class="text-gray-500 dark:text-gray-400">{{ __('Edit') }}</span>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Produk') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Edit produk') }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden max-w-xl">
        <div class="p-6">
            <form action="{{ route('products.update', $product) }}" method="POST" class="max-w-2xl">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <x-forms.input label="Nama Produk" name="nama_produk" type="text" value="{{ old('nama_produk', $product->nama_produk) }}" required />
                </div>

                <div class="mb-4">
                    <x-forms.input label="Satuan" name="satuan" type="text" value="{{ old('satuan', $product->satuan) }}" required />
                </div>

                <div class="mb-6">
                    <x-forms.input label="Harga Beli" name="harga_beli" type="number" min="0" value="{{ old('harga_beli', $product->harga_beli) }}" required />
                </div>

                <div class="flex gap-3">
                    <x-button type="primary">{{ __('Simpan') }}</x-button>
                    <x-button type="secondary" tag="a" href="{{ route('products.index') }}">{{ __('Batal') }}</x-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
