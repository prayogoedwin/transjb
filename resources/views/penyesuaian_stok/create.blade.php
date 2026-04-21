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
        <span class="text-gray-500 dark:text-gray-400">{{ __('Tambah') }}</span>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Tambah Penyesuaian Stok') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Tambah atau kurangi stok produk secara manual') }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden max-w-2xl">
        <div class="p-6">
            <form action="{{ route('penyesuaian_stok.store') }}" method="POST" class="max-w-2xl" @submit="formSubmitted = true">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Produk') }} *</label>
                    <select name="produk_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required>
                        <option value="">{{ __('Pilih Produk') }}</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" {{ old('produk_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_produk }} ({{ $p->satuan }})
                            </option>
                        @endforeach
                    </select>
                    @error('produk_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Status') }} *</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required>
                        <option value="">{{ __('Pilih Status') }}</option>
                        <option value="in" {{ old('status') === 'in' ? 'selected' : '' }}>{{ __('Masuk (Tambah Stok)') }}</option>
                        <option value="out" {{ old('status') === 'out' ? 'selected' : '' }}>{{ __('Keluar (Kurangi Stok)') }}</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Jumlah') }} *</label>
                    <input type="number" name="jumlah" value="{{ old('jumlah') }}" step="0.0001" min="0.0001"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100"
                        required />
                    @error('jumlah')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Keterangan') }}</label>
                    <textarea name="keterangan" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100"
                        placeholder="{{ __('Alasan penyesuaian stok...') }}">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <x-button type="primary" id="btn-simpan" x-bind:disabled="formSubmitted" x-text="formSubmitted ? '{{ __('Menyimpan...') }}' : '{{ __('Simpan') }}'"></x-button>
                    <a href="{{ route('penyesuaian_stok.index') }}">
                        <x-button type="secondary">{{ __('Batal') }}</x-button>
                    </a>
                </div>
            </form>

            <script>
                document.querySelector('form').addEventListener('submit', function () {
                    const btn = document.getElementById('btn-simpan');
                    btn.disabled = true;
                    btn.textContent = '{{ __('Menyimpan...') }}';
                });
            </script>
        </div>
    </div>
</x-layouts.app>
