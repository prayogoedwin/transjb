<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <x-icons.chevron-right />
        <a href="{{ route('pembelian.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Pembelian') }}</a>
        <x-icons.chevron-right />
        <span class="text-gray-500 dark:text-gray-400">{{ __('Edit') }}</span>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Pembelian') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Perbarui data pembelian') }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <form action="{{ route('pembelian.update', $pembelian) }}" method="POST" class="max-w-2xl" id="pembelianForm">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Nasabah') }} *</label>
                    <select name="nasabah_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required>
                        <option value="">{{ __('Pilih Nasabah') }}</option>
                        @foreach($nasabah as $nb)
                            <option value="{{ $nb->id }}" {{ old('nasabah_id', $pembelian->nasabah_id) == $nb->id ? 'selected' : '' }}>{{ $nb->nama }}</option>
                        @endforeach
                    </select>
                    @error('nasabah_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Produk') }} *</label>
                    <select name="produk_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required>
                        <option value="">{{ __('Pilih Produk') }}</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" {{ old('produk_id', $pembelian->produk_id) == $p->id ? 'selected' : '' }}>{{ $p->nama_produk }} ({{ $p->satuan }})</option>
                        @endforeach
                    </select>
                    @error('produk_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Harga Satuan Beli') }} *</label>
                    <input type="number" name="harga_satuan_beli" step="0.01" min="0" value="{{ old('harga_satuan_beli', $pembelian->harga_satuan_beli) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required />
                    @error('harga_satuan_beli')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Satuan') }} *</label>
                    <input type="text" name="satuan" value="{{ old('satuan', $pembelian->satuan) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required />
                    @error('satuan')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Total Berat') }} *</label>
                    <input type="number" name="total_berat" step="0.0001" min="0" value="{{ old('total_berat', $pembelian->total_berat) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required />
                    @error('total_berat')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Biaya Admin (%)') }} *</label>
                    <input type="number" name="potongan" step="0.01" min="0" max="100" value="{{ old('potongan', $pembelian->potongan) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required />
                    @error('potongan')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Keterangan') }}</label>
                    <textarea name="keterangan" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" placeholder="{{ __('Catatan tambahan tentang pembelian ini') }}">{{ old('keterangan', $pembelian->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-blue-700 dark:text-blue-300">{{ __('Total Harga') }}</span>
                            <span class="font-semibold text-blue-900 dark:text-blue-100" id="summary-total-price">Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-700 dark:text-blue-300">{{ __('Biaya Admin') }}</span>
                            <span class="font-semibold text-blue-900 dark:text-blue-100" id="summary-admin-fee">Rp {{ number_format($pembelian->biaya_admin, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-blue-200 dark:border-blue-700 pt-2 mt-2 flex justify-between">
                            <span class="text-blue-900 dark:text-blue-100">{{ __('Harga Akhir') }}</span>
                            <span class="font-bold text-lg text-blue-900 dark:text-blue-100" id="summary-final-price">Rp {{ number_format($pembelian->harga_akhir, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <x-button type="primary">{{ __('Perbarui') }}</x-button>
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
            const biayaPersen = parseFloat(document.querySelector('input[name="potongan"]').value) || 0;

            const totalHarga = hargaSatuan * totalBerat;
            const biayaAdmin = (totalHarga * biayaPersen) / 100;
            const hargaAkhir = totalHarga - biayaAdmin;

            document.getElementById('summary-total-price').textContent = 'Rp ' + totalHarga.toLocaleString('id-ID', {maximumFractionDigits: 0});
            document.getElementById('summary-admin-fee').textContent = 'Rp ' + biayaAdmin.toLocaleString('id-ID', {maximumFractionDigits: 0});
            document.getElementById('summary-final-price').textContent = 'Rp ' + hargaAkhir.toLocaleString('id-ID', {maximumFractionDigits: 0});
        }

        document.querySelector('input[name="harga_satuan_beli"]').addEventListener('change', calculateTotals);
        document.querySelector('input[name="total_berat"]').addEventListener('change', calculateTotals);
        document.querySelector('input[name="potongan"]').addEventListener('change', calculateTotals);

        // Initial calculation
        calculateTotals();
    </script>
</x-layouts.app>
