<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <x-icons.chevron-right />
        <a href="{{ route('pembelian.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Pembelian') }}</a>
        <x-icons.chevron-right />
        <span class="text-gray-500 dark:text-gray-400">{{ __('Tambah') }}</span>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Tambah Pembelian') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Tambahkan pembelian baru') }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden max-w-2xl">
        <div class="p-6">
<<<<<<< HEAD
            <form id="pembelian-form"
                x-data="{
                    hargaSatuan: 0,
                    satuan: '',
                    totalBerat: {{ old('total_berat', 0) }},
                    potongan: {{ old('potongan', 0) }},
                    get beratSetelahPotong() { return this.totalBerat - (this.totalBerat * this.potongan / 100); },
                    get totalHarga() { return this.hargaSatuan * this.totalBerat; },
                    get biayaAdmin() { return this.totalHarga * this.potongan / 100; },
                    get hargaAkhir() { return this.totalHarga - this.biayaAdmin; },
                    onProductChange(el) {
                        const opt = el.options[el.selectedIndex];
                        this.hargaSatuan = parseFloat(opt.dataset.harga) || 0;
                        this.satuan = opt.dataset.satuan || '';
                    },
                    fmt(val) { return 'Rp ' + Math.round(val).toLocaleString('id-ID'); },
                    fmtBerat(val) { return val.toLocaleString('id-ID', {maximumFractionDigits: 4}); },
                    init() {
                        const sel = this.$el.querySelector('[name=produk_id]');
                        if (sel && sel.value) this.onProductChange(sel);
                    }
                }"
                action="{{ route('pembelian.store') }}" method="POST" class="max-w-2xl">
=======
            <form id="pembelian-form" action="{{ route('pembelian.store') }}" method="POST" class="max-w-2xl" @submit="formSubmitted = true">
>>>>>>> master
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
                    <select id="produk_id" name="produk_id" @change="onProductChange($el)" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required>
                        <option value="">{{ __('Pilih Produk') }}</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" data-satuan="{{ $p->satuan }}" data-harga="{{ $p->harga_beli }}" {{ old('produk_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_produk }} - {{ formatCurrency($p->harga_beli) }} / {{ $p->satuan }}</option>
                        @endforeach
                    </select>
                    @error('produk_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Total Berat') }} *</label>
                    <input type="number" name="total_berat" x-model.number="totalBerat" value="{{ old('total_berat', 0) }}" step="0.0001" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required />
                    @error('total_berat')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Potongan (%)') }} *</label>
                    <input type="number" name="potongan" x-model.number="potongan" max="100" value="{{ old('potongan', 0) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required />
                    @error('potongan')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg mb-6">
                    <table class="w-full text-sm">
                        <tbody>
                            <tr>
                                <td class="py-1 text-blue-700 dark:text-blue-300">{{ __('Harga Satuan') }}</td>
                                <td class="py-1 text-right font-semibold text-blue-900 dark:text-blue-100" x-text="fmt(hargaSatuan)">Rp 0</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="py-1 text-blue-700 dark:text-blue-300">{{ __('Berat setelah potong') }}</td>
                                <td class="py-1 text-right font-semibold text-blue-900 dark:text-blue-100" x-text="fmtBerat(beratSetelahPotong)">0</td>
                                <td class="pl-1 text-center font-semibold text-blue-900 dark:text-blue-100" x-text="satuan"></td>
                                <td class="text-center font-semibold text-blue-900 dark:text-blue-100">x</td>
                            </tr>
                            <tr class="border-t border-blue-200 dark:border-blue-700">
                                <td class="pt-2 text-blue-900 dark:text-blue-100 font-semibold">{{ __('Harga Akhir') }}</td>
                                <td class="pt-2 text-right font-bold text-lg text-blue-900 dark:text-blue-100" x-text="fmt(hargaAkhir)">Rp 0</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Keterangan') }}</label>
                    <textarea name="keterangan" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" placeholder="{{ __('Catatan tambahan tentang pembelian ini') }}">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" id="print_invoice" name="print_invoice" value="1" class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500" {{ old('print_invoice') ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Cetak invoice') }}</span>
                    </label>
                </div>

                <input type="hidden" name="harga_satuan_beli" :value="hargaSatuan">
                <input type="hidden" name="satuan" :value="satuan">
                <input type="hidden" name="berat_setelah_potong" :value="beratSetelahPotong">
                <input type="hidden" name="biaya_admin" :value="biayaAdmin">
                <input type="hidden" name="harga_akhir" :value="hargaAkhir">

                <div class="flex gap-3">
                    <x-button type="primary" :disabled="formSubmitted" x-text="formSubmitted ? '{{ __('Menyimpan...') }}' : '{{ __('Simpan') }}'"></x-button>
                    <a href="{{ route('pembelian.index') }}">
                        <x-button type="secondary">{{ __('Batal') }}</x-button>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('pembelian-form').addEventListener('submit', function (e) {
            const printCheckbox = document.getElementById('print_invoice');

            if (printCheckbox.checked) {
                e.preventDefault();

                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: { 'Accept': 'application/json' }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    window.open(data.print_url, '_blank');
                    setTimeout(() => { window.location.href = data.redirect_url; }, 500);
                })
                .catch(() => alert('Terjadi kesalahan saat menyimpan data'));
            }
        });
    </script>
</x-layouts.app>
