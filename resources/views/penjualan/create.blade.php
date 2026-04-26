<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <x-icons.chevron-right />
        <a href="{{ route('penjualan.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Penjualan') }}</a>
        <x-icons.chevron-right />
        <span class="text-gray-500 dark:text-gray-400">{{ __('Tambah') }}</span>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Tambah Penjualan') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Tambahkan penjualan baru') }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <form id="penjualan-form"  action="{{ route('penjualan.store') }}" method="POST" class="max-w-3xl" @submit="formSubmitted = true">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Nama Pembeli') }} *</label>
                    <input type="text" name="nama_customer" id="nama_customer" value="{{ old('nama_customer') }}" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" />
                    @error('nama_customer')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Nopol') }}</label>
                    <input type="text" name="nopol" id="nopol" value="{{ old('nopol') }}" placeholder="{{ __('cth: B 1234 CD') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" />
                    @error('nopol')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Keterangan') }}</label>
                    <textarea name="keterangan" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Detail Item') }}</label>
                        <x-button type="primary" id="addItemBtn" tag="a" href="#">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            {{ __('Tambah Item') }}
                        </x-button>
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

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" id="print_invoice" name="print_invoice" value="1" class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500" {{ old('print_invoice') ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Cetak invoice') }}</span>
                    </label>
                </div>

                <div class="flex gap-3">
                    <x-button type="primary" x-bind:disabled="formSubmitted" x-text="formSubmitted ? '{{ __('Menyimpan...') }}' : '{{ __('Simpan') }}'"></x-button>
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
                <select name="details[INDEX][produk_id]" class="produk-select w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded  focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-gray-100">
                    <option value="">-- {{ __('Pilih') }} --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" data-satuan="{{ $p->satuan }}">{{ $p->nama_produk }}</option>
                    @endforeach
                </select>
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
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Jumlah') }}</label>
                <input type="number" name="details[INDEX][jumlah]" class="jumlah w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-600 dark:text-gray-100" step="0.0001" min="0">
            </div>
            <div class="w-24">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Total') }}</label>
                <div class="w-full px-2 py-1 text-xs font-medium bg-gray-100 dark:bg-gray-500 text-gray-900 dark:text-white rounded item-total">Rp 0</div>
            </div>
            <div class="flex items-end">
                <a href="#" class="remove-item-btn px-2 py-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-xs">
                    {{ __('Hapus') }}
                </a>
            </div>
        </div>
    </template>

    <script>
        let itemCount = 0;
        const products = @json($products);

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

        function addItem() {
            const template = document.getElementById('itemTemplate');
            const content = template.innerHTML.replace(/INDEX/g, itemCount);
            const fragment = document.createElement('div');
            fragment.innerHTML = content;
            const newItem = fragment.firstElementChild;
            
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

        document.getElementById('addItemBtn').addEventListener('click', function (e) {
            e.preventDefault();
            addItem();
        });
        addItem();

        document.getElementById('penjualan-form').addEventListener('submit', function(e) {
            const printCheckbox = document.getElementById('print_invoice');
            if (!printCheckbox.checked) {
                return;
            }

            e.preventDefault();
            const formData = new FormData(this);
            fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json' }
                })
                .then(response => response.ok ? response.json() : Promise.reject())
                .then(data => {
                    window.open(data.print_url, '_blank');
                    setTimeout(() => { window.location.href = data.redirect_url; }, 500);
                })
                .catch(() => alert('{{ __("Error") }}'));
        });
    </script>
</x-layouts.app>
