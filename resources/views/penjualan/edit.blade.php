<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('penjualan.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Penjualan') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Edit') }}</span>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Penjualan') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Ubah data penjualan') }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <form id="penjualan-form" action="{{ route('penjualan.update', $penjualan) }}" method="POST" class="max-w-2xl">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Customer') }} *</label>
                    <select name="customer_id" id="customer_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                        <option value="">{{ __('Pilih Customer') }}</option>
                        @foreach($nasabah as $n)
                            <option value="{{ $n->id }}" {{ $penjualan->customer_id == $n->id ? 'selected' : '' }}>{{ $n->nama }}</option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Nama Customer') }} *</label>
                    <input type="text" name="nama_customer" id="nama_customer" value="{{ $penjualan->nama_customer }}" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" />
                    @error('nama_customer')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Nopol') }}</label>
                    <input type="text" name="nopol" id="nopol" value="{{ $penjualan->nopol }}" placeholder="{{ __('cth: B 1234 CD') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" />
                    @error('nopol')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Keterangan') }}</label>
                    <textarea name="keterangan" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">{{ $penjualan->keterangan }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Detail Item') }}</label>
                        <button type="button" id="addItemBtn" class="inline-flex items-center px-3 py-1 text-sm bg-green-600 text-white rounded hover:bg-green-700">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            {{ __('Tambah Item') }}
                        </button>
                    </div>
                    <div id="itemsContainer" class="mt-4 space-y-3">
                        <!-- Items will be added here -->
                    </div>
                </div>

                <!-- Summary -->
                <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg mb-6">
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-blue-700 dark:text-blue-300">{{ __('Total Item') }}</span>
                            <span class="font-semibold text-blue-900 dark:text-blue-100" id="totalItems">0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-700 dark:text-blue-300">{{ __('Total Jumlah') }}</span>
                            <span class="font-semibold text-blue-900 dark:text-blue-100" id="totalJumlah">0</span>
                        </div>
                        <div class="border-t border-blue-200 dark:border-blue-700 pt-2 mt-2 flex justify-between">
                            <span class="text-blue-900 dark:text-blue-100">{{ __('Total Penjualan') }}</span>
                            <span class="font-bold text-lg text-blue-900 dark:text-blue-100" id="totalHarga">Rp 0</span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <x-button type="primary">{{ __('Simpan') }}</x-button>
                    <a href="{{ route('penjualan.index') }}">
                        <x-button type="secondary">{{ __('Batal') }}</x-button>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <template id="itemTemplate">
        <div class="item-row bg-gray-50 dark:bg-gray-700 rounded p-3 border border-gray-200 dark:border-gray-600 flex gap-2">
            <div class="flex-1">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Produk') }}</label>
                <select name="details[INDEX][produk_id]" class="produk-select w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-gray-100">
                    <option value="">-- {{ __('Pilih') }} --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" data-satuan="{{ $p->satuan }}">{{ $p->nama_produk }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="details[INDEX][id]" class="detail-id">
            </div>
            <div class="w-32">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Harga') }}</label>
                <input type="number" name="details[INDEX][harga_satuan]" class="harga-satuan w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-600 dark:text-gray-100" step="0.01" min="0">
            </div>
            <div class="w-20">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Satuan') }}</label>
                <input type="text" name="details[INDEX][satuan]" class="satuan w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded bg-gray-100 dark:bg-gray-500 dark:text-gray-100" readonly>
            </div>
            <div class="w-20">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Qty') }}</label>
                <input type="number" name="details[INDEX][jumlah]" class="jumlah w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-600 dark:text-gray-100" step="0.0001" min="0">
            </div>
            <div class="w-24">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Total') }}</label>
                <div class="w-full px-2 py-1 text-xs font-medium bg-gray-100 dark:bg-gray-500 text-gray-900 dark:text-white rounded item-total">Rp 0</div>
            </div>
            <div class="flex items-end">
                <button type="button" class="remove-item-btn px-2 py-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-xs">
                    {{ __('Hapus') }}
                </button>
            </div>
        </div>
    </template>

    <script>
        let itemCount = 0;
        const products = @json($products);
        const existingDetails = @json($penjualan->details);

        function updateCalculations() {
            let totalItems = 0;
            let totalJumlah = 0;
            let totalHarga = 0;

            document.querySelectorAll('.item-row').forEach((row) => {
                totalItems++;
                
                const hargaSatuan = parseFloat(row.querySelector('.harga-satuan').value) || 0;
                const jumlah = parseFloat(row.querySelector('.jumlah').value) || 0;
                const itemTotal = hargaSatuan * jumlah;
                
                totalJumlah += jumlah;
                totalHarga += itemTotal;
                
                row.querySelector('.item-total').textContent = 'Rp ' + itemTotal.toLocaleString('id-ID', {maximumFractionDigits: 0});
            });

            document.getElementById('totalItems').textContent = totalItems;
            document.getElementById('totalJumlah').textContent = totalJumlah.toFixed(4);
            document.getElementById('totalHarga').textContent = 'Rp ' + totalHarga.toLocaleString('id-ID', {maximumFractionDigits: 0});
        }

        function addItem(detail = null) {
            const template = document.getElementById('itemTemplate');
            const content = template.innerHTML.replace(/INDEX/g, itemCount);
            const fragment = document.createElement('div');
            fragment.innerHTML = content;
            const newItem = fragment.firstElementChild;
            
            if (detail) {
                newItem.querySelector('.detail-id').value = detail.id;
                newItem.querySelector('.produk-select').value = detail.produk_id;
                newItem.querySelector('.harga-satuan').value = detail.harga_satuan;
                newItem.querySelector('.satuan').value = detail.satuan;
                newItem.querySelector('.jumlah').value = detail.jumlah;
            }
            
            document.getElementById('itemsContainer').appendChild(newItem);
            
            newItem.querySelector('.produk-select').addEventListener('change', function() {
                newItem.querySelector('.satuan').value = this.options[this.selectedIndex].dataset.satuan || 'kg';
            });

            newItem.querySelector('.harga-satuan').addEventListener('input', updateCalculations);
            newItem.querySelector('.jumlah').addEventListener('input', updateCalculations);

            newItem.querySelector('.remove-item-btn').addEventListener('click', function() {
                newItem.remove();
                updateCalculations();
            });

            itemCount++;
            updateCalculations();
        }

        document.getElementById('addItemBtn').addEventListener('click', () => addItem());
        
        existingDetails.forEach(detail => addItem(detail));
        if (existingDetails.length === 0) addItem();

        document.getElementById('customer_id').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            if (selected.value) {
                document.getElementById('nama_customer').value = selected.text;
            }
        });
    </script>
</x-layouts.app>
