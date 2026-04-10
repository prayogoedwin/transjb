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
        <span class="text-gray-500 dark:text-gray-400">{{ __('Tambah') }}</span>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Tambah Pembelian') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Tambahkan pembelian baru') }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <form action="{{ route('pembelian.store') }}" method="POST" class="max-w-2xl" id="pembelianForm">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Produk') }} *</label>
                    <select name="produk_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required>
                        <option value="">{{ __('Pilih Produk') }}</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" {{ old('produk_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_produk }} ({{ $p->satuan }})</option>
                        @endforeach
                    </select>
                    @error('produk_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Harga Satuan Beli') }} *</label>
                    <input type="number" name="harga_satuan_beli" step="0.01" min="0" value="{{ old('harga_satuan_beli', 0) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required />
                    @error('harga_satuan_beli')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Satuan') }} *</label>
                    <input type="text" name="satuan" value="{{ old('satuan', 'kg') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required />
                    @error('satuan')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Total Berat') }} *</label>
                    <input type="number" name="total_berat" step="0.0001" min="0" value="{{ old('total_berat', 0) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required />
                    @error('total_berat')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Biaya Admin (%)') }} *</label>
                    <input type="number" name="biaya_admin_persen" step="0.01" min="0" max="100" value="{{ old('biaya_admin_persen', 0) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required />
                    @error('biaya_admin_persen')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                    <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-3">{{ __('Ringkasan') }}</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-blue-700 dark:text-blue-300">{{ __('Total Harga') }}</span>
                            <span class="font-semibold text-blue-900 dark:text-blue-100" id="summary-total-price">Rp 0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-700 dark:text-blue-300">{{ __('Biaya Admin') }}</span>
                            <span class="font-semibold text-blue-900 dark:text-blue-100" id="summary-admin-fee">Rp 0</span>
                        </div>
                        <div class="border-t border-blue-200 dark:border-blue-700 pt-2 mt-2 flex justify-between">
                            <span class="text-blue-900 dark:text-blue-100">{{ __('Harga Akhir') }}</span>
                            <span class="font-bold text-lg text-blue-900 dark:text-blue-100" id="summary-final-price">Rp 0</span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <x-button type="primary">{{ __('Simpan') }}</x-button>
                    <a href="{{ route('pembelian.index') }}">
                        <x-button type="secondary">{{ __('Batal') }}</x-button>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function calculateTotals() {
            const hargaSatuan = parseFloat(document.querySelector('input[name="harga_satuan_beli"]').value) || 0;
            const totalBerat = parseFloat(document.querySelector('input[name="total_berat"]').value) || 0;
            const biayaPersen = parseFloat(document.querySelector('input[name="biaya_admin_persen"]').value) || 0;

            const totalHarga = hargaSatuan * totalBerat;
            const biayaAdmin = (totalHarga * biayaPersen) / 100;
            const hargaAkhir = totalHarga - biayaAdmin;

            document.getElementById('summary-total-price').textContent = 'Rp ' + totalHarga.toLocaleString('id-ID', {maximumFractionDigits: 0});
            document.getElementById('summary-admin-fee').textContent = 'Rp ' + biayaAdmin.toLocaleString('id-ID', {maximumFractionDigits: 0});
            document.getElementById('summary-final-price').textContent = 'Rp ' + hargaAkhir.toLocaleString('id-ID', {maximumFractionDigits: 0});
        }

        document.querySelector('input[name="harga_satuan_beli"]').addEventListener('change', calculateTotals);
        document.querySelector('input[name="total_berat"]').addEventListener('change', calculateTotals);
        document.querySelector('input[name="biaya_admin_persen"]').addEventListener('change', calculateTotals);

        // Initial calculation
        calculateTotals();
    </script>
</x-layouts.app>
