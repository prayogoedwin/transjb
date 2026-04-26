<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <x-icons.chevron-right />
        <a href="{{ route('simpan_pinjam.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Bayar & Hutang') }}</a>
        <x-icons.chevron-right />
        <span class="text-gray-500 dark:text-gray-400">{{ __('Tambah') }}</span>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Tambah Bayar & Hutang') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Tambahkan catatan bayar atau hutang') }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <form id="simpan-pinjam-form" action="{{ route('simpan_pinjam.store') }}" method="POST" class="max-w-2xl" @submit="formSubmitted = true">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Nasabah') }} *</label>
                    <select name="nasabah_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required>
                        <option value="">{{ __('Pilih Nasabah') }}</option>
                        @foreach($nasabah as $n)
                            <option value="{{ $n->id }}" data-sisa-simpanan="{{ (int) round($n->sisa_simpanan ?? 0) }}" {{ old('nasabah_id') == $n->id ? 'selected' : '' }}>{{ $n->nama }}</option>
                        @endforeach
                    </select>
                    @error('nasabah_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Tipe') }} *</label>
                    <select name="tipe" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required>
                        <option value="">{{ __('Pilih Tipe') }}</option>
                        <option value="bayar_simpanan" {{ old('tipe') == 'bayar_simpanan' ? 'selected' : '' }}>{{ __('Bayar dari Simpanan') }}</option>
                        <option value="hutang" {{ old('tipe') == 'hutang' ? 'selected' : '' }}>{{ __('Hutang') }}</option>
                        <option value="transaksi" {{ old('tipe') == 'transaksi' ? 'selected' : '' }}>{{ __('Transaksi') }}</option>
                        <option value="ambil_simpanan" {{ old('tipe') == 'ambil_simpanan' ? 'selected' : '' }}>{{ __('Ambil Simpanan') }}</option>
                        <option value="simpan" {{ old('tipe') == 'simpan' ? 'selected' : '' }}>{{ __('Simpan') }}</option>
                    </select>
                    @error('tipe')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <x-forms.input label="Nominal" name="nominal" type="number" step="0.01" min="0" value="{{ old('nominal', 0) }}" required />
                    <p id="sisa-simpanan-info" class="mt-2 text-sm text-emerald-600 dark:text-emerald-400 hidden"></p>
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" id="print_receipt" name="print_receipt" value="1" class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500" {{ old('print_receipt') ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Cetak struk langsung setelah simpan') }}</span>
                    </label>
                </div>

                <div class="flex gap-3">
                    <x-button type="primary" x-bind:disabled="formSubmitted" x-text="formSubmitted ? '{{ __('Menyimpan...') }}' : '{{ __('Simpan') }}'"></x-button>
                    <a href="{{ route('simpan_pinjam.index') }}">
                        <x-button type="secondary">{{ __('Batal') }}</x-button>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const nasabahSelect = document.querySelector('select[name="nasabah_id"]');
            const tipeSelect = document.querySelector('select[name="tipe"]');
            const nominalInput = document.querySelector('input[name="nominal"]');
            const saldoInfo = document.getElementById('sisa-simpanan-info');
            const tipeButuhSaldo = ['bayar_simpanan', 'ambil_simpanan'];

            const fmtRupiah = (value) => 'Rp ' + Math.round(Number(value || 0)).toLocaleString('id-ID');

            function getSelectedSaldo() {
                const opt = nasabahSelect?.options[nasabahSelect.selectedIndex];
                return Number(opt?.dataset?.sisaSimpanan || 0);
            }

            function syncSaldoInfo() {
                if (!nasabahSelect || !tipeSelect || !nominalInput || !saldoInfo) return;
                const butuhSaldo = tipeButuhSaldo.includes(tipeSelect.value);
                const saldo = getSelectedSaldo();

                if (butuhSaldo) {
                    nominalInput.max = String(saldo);
                    nominalInput.step = '1';
                    saldoInfo.textContent = `Sisa simpanan tersedia: ${fmtRupiah(saldo)} (maksimal input)`;
                    saldoInfo.classList.remove('hidden');
                } else {
                    nominalInput.removeAttribute('max');
                    nominalInput.step = '0.01';
                    saldoInfo.classList.add('hidden');
                    saldoInfo.textContent = '';
                }
            }

            nasabahSelect?.addEventListener('change', syncSaldoInfo);
            tipeSelect?.addEventListener('change', syncSaldoInfo);
            syncSaldoInfo();
        })();

        document.getElementById('simpan-pinjam-form').addEventListener('submit', function(e) {
            const printCheckbox = document.getElementById('print_receipt');
            
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
</x-layouts.app>
