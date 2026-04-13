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

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden max-w-2xl">
        <div class="p-6">
            <form id="pembelian-form" action="{{ route('pembelian.store') }}" method="POST" class="max-w-2xl">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Nasabah') }} *</label>
                    <select name="nasabah_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required>
                        <option value="">{{ __('Pilih Nasabah') }}</option>
                        @foreach($nasabah as $nb)
                            <option value="{{ $nb->id }}" {{ old('nasabah_id') == $nb->id ? 'selected' : '' }}>{{ $nb->nama }}</option>
                        @endforeach
                    </select>
                    @error('nasabah_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Produk') }} *</label>
                    <select id="produk_id" name="produk_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required>
                        <option value="">{{ __('Pilih Produk') }}</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" data-satuan="{{ $p->satuan }}" {{ old('produk_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_produk }} ({{ $p->satuan }})</option>
                        @endforeach
                    </select>
                    @error('produk_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Satuan') }}</label>
                    <input type="text" id="satuan_display" readonly class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 dark:text-gray-100 text-gray-600" />
                    <input type="hidden" name="satuan" id="satuan_input" value="{{ old('satuan', '') }}" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Total Berat') }} *</label>
                    <input type="number" name="total_berat" step="0.0001" min="0" value="{{ old('total_berat', 0) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required />
                    @error('total_berat')
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

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Biaya Admin (%)') }} *</label>
                    <input type="number" name="biaya_admin_persen" step="0.01" min="0" max="100" value="{{ old('biaya_admin_persen', 0) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required />
                    @error('biaya_admin_persen')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Keterangan') }}</label>
                    <textarea name="keterangan" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" placeholder="{{ __('Catatan tambahan tentang pembelian ini') }}">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg mb-6">
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

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" id="print_invoice" name="print_invoice" value="1" class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500" {{ old('print_invoice') ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Cetak invoice') }}</span>
                    </label>
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
        // Calculate summary
        const form = document.getElementById('pembelian-form');
        const hargaSatuanInput = form.querySelector('input[name="harga_satuan_beli"]');
        const totalBeratInput = form.querySelector('input[name="total_berat"]');
        const biayaAdminPersenInput = form.querySelector('input[name="biaya_admin_persen"]');
        
        function updateSummary() {
            const hargaSatuan = parseFloat(hargaSatuanInput.value) || 0;
            const totalBerat = parseFloat(totalBeratInput.value) || 0;
            const biayaAdminPersen = parseFloat(biayaAdminPersenInput.value) || 0;
            
            const totalHarga = hargaSatuan * totalBerat;
            const biayaAdmin = (totalHarga * biayaAdminPersen) / 100;
            const hargaAkhir = totalHarga - biayaAdmin;
            
            document.getElementById('summary-total-price').textContent = 'Rp ' + totalHarga.toLocaleString('id-ID', {maximumFractionDigits: 0});
            document.getElementById('summary-admin-fee').textContent = 'Rp ' + biayaAdmin.toLocaleString('id-ID', {maximumFractionDigits: 0});
            document.getElementById('summary-final-price').textContent = 'Rp ' + hargaAkhir.toLocaleString('id-ID', {maximumFractionDigits: 0});
        }

        hargaSatuanInput.addEventListener('change', updateSummary);
        totalBeratInput.addEventListener('change', updateSummary);
        biayaAdminPersenInput.addEventListener('change', updateSummary);

        // Handle form submission with print invoice
        form.addEventListener('submit', function(e) {
            const printCheckbox = document.getElementById('print_invoice');
            
            // Jika checkbox cetak dicentang
            if (printCheckbox.checked) {
                e.preventDefault();
                
                // Collect form data
                const formData = new FormData(this);
                
                // Submit via AJAX dengan Accept JSON header
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    // Buka PDF di tab baru
                    window.open(data.print_url, '_blank');
                    
                    // Redirect halaman saat ini ke index
                    setTimeout(() => {
                        window.location.href = data.redirect_url;
                    }, 500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menyimpan data');
                });
            }
            // Jika checkbox tidak dicentang, biarkan form submit normal
        });
    </script>

    <script>
        // Auto-fill satuan from selected product
        const produkSelect = document.getElementById('produk_id');
        const satuanDisplay = document.getElementById('satuan_display');
        const satuanInput = document.getElementById('satuan_input');

        function updateSatuan() {
            const selectedOption = produkSelect.options[produkSelect.selectedIndex];
            const satuan = selectedOption.getAttribute('data-satuan') || '';
            satuanDisplay.value = satuan;
            satuanInput.value = satuan;
        }

        // Handle product selection change
        produkSelect.addEventListener('change', updateSatuan);

        // Initialize satuan on page load if product already selected
        window.addEventListener('load', updateSatuan);
    </script>

    <script>
            const hargaSatuan = parseFloat(document.querySelector('input[name="harga_satuan_beli"]').value) || 0;
            const totalBerat = parseFloat(document.querySelector('input[name="total_berat"]').value) || 0;
            const biayaPersen = parseFloat(document.querySelector('input[name="biaya_admin_persen"]').value) || 0;

            const totalHarga = hargaSatuan * totalBerat;
            const biayaAdmin = (totalHarga * biayaPersen) / 100;
            const hargaAkhir = totalHarga - biayaAdmin;

            document.getElementById('summary-total-price').textContent = 'Rp ' + totalHarga.toLocaleString('id-ID', {maximumFractionDigits: 0});
            document.getElementById('summary-admin-fee').textContent = 'Rp ' + biayaAdmin.toLocaleString('id-ID', {maximumFractionDigits: 0});
            document.getElementById('summary-final-price').textContent = 'Rp ' + hargaAkhir.toLocaleString('id-ID', {maximumFractionDigits: 0});

        document.querySelector('input[name="harga_satuan_beli"]').addEventListener('change', calculateTotals);
        document.querySelector('input[name="total_berat"]').addEventListener('change', calculateTotals);
        document.querySelector('input[name="biaya_admin_persen"]').addEventListener('change', calculateTotals);

        // Initial calculation
        calculateTotals();
    </script>
</x-layouts.app>
