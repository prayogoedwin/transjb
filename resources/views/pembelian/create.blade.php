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
            <form id="pembelian-form"
                x-data="{
                    hargaSatuan: 0,
                    satuan: '',
                    totalBerat: {{ old('total_berat', 0) }},
                    potongan: {{ old('potongan', 0) }},
                    bayarNominal: {{ old('bayar_nominal', 0) }},
                    simpanNominal: {{ old('simpan_nominal', 0) }},
                    ambilNominal: {{ old('ambil_nominal', 0) }},
                    sisaHutangNasabah: 0,
                    rupiah(val) { return Math.round(Number(val) || 0); },
                    get beratSetelahPotong() { return this.totalBerat - (this.totalBerat * this.potongan / 100); },
                    get totalHarga() { return this.rupiah(this.hargaSatuan * this.totalBerat); },
                    get biayaAdmin() { return this.rupiah(this.totalHarga * this.potongan / 100); },
                    get hargaAkhir() { return Math.max(0, this.rupiah(this.totalHarga - this.biayaAdmin)); },
                    get totalAlokasi() { return this.bayarNominal + this.simpanNominal + this.ambilNominal; },
                    get sisaAlokasi() { return this.hargaAkhir - this.totalAlokasi; },
                    get maxBayar() { return Math.max(0, this.hargaAkhir - this.simpanNominal - this.ambilNominal); },
                    get maxSimpan() { return Math.max(0, this.hargaAkhir - this.bayarNominal - this.ambilNominal); },
                    get maxAmbil() { return Math.max(0, this.hargaAkhir - this.bayarNominal - this.simpanNominal); },
                    onProductChange(el) {
                        const opt = el.options[el.selectedIndex];
                        this.hargaSatuan = parseFloat(opt.dataset.harga) || 0;
                        this.satuan = opt.dataset.satuan || '';
                    },
                    onNasabahChange(el) {
                        const opt = el.options[el.selectedIndex];
                        this.sisaHutangNasabah = this.rupiah(opt?.dataset?.sisaHutang || 0);
                    },
                    fmt(val) { return 'Rp ' + Math.round(val).toLocaleString('id-ID'); },
                    fmt2(val) { return 'Rp ' + Math.round(val); },
                    fmtBerat(val) { return val.toLocaleString('id-ID', {maximumFractionDigits: 4}); },
                    init() {
                        const sel = this.$el.querySelector('[name=produk_id]');
                        if (sel && sel.value) this.onProductChange(sel);
                        const nasabahSel = this.$el.querySelector('[name=nasabah_id]');
                        if (nasabahSel) this.onNasabahChange(nasabahSel);

                        this.$watch('hargaAkhir', (value) => {
                            this.simpanNominal = value;
                        });
                    }
                }"
                action="{{ route('pembelian.store') }}" method="POST" class="max-w-2xl" @submit="formSubmitted = true">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Nasabah') }} *</label>
                    <select id="nasabah_id" name="nasabah_id" @change="onNasabahChange($el)" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required>
                        <option value="">{{ __('Pilih Nasabah') }}</option>
                        @foreach($nasabah as $nb)
                        <option value="{{ $nb->id }}" data-sisa-hutang="{{ (int) round($nb->sisa_hutang ?? 0) }}" {{ old('nasabah_id') == $nb->id ? 'selected' : '' }}>{{ $nb->nama }}</option>
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
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Harga Satuan Beli') }} *</label>
                    <input type="number" name="harga_satuan_beli" x-model.number="hargaSatuan" value="{{ old('harga_satuan_beli', 0) }}" step="1" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Harga produk hanya referensi. Admin bisa ubah harga satuan per transaksi.') }}</p>
                    @error('harga_satuan_beli')
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
                                <td class="pt-2 text-right font-bold text-lg text-blue-900 dark:text-blue-100" x-text="fmt2(hargaAkhir)">Rp 0</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100 mb-3">{{ __('Alokasi dari Harga Akhir (Masuk Riwayat)') }}</h3>
                    <p class="mb-3 text-sm text-red-600 dark:text-red-400">
                        {{ __('Sisa Hutang Nasabah') }}: <span class="font-semibold" x-text="fmt(sisaHutangNasabah)">Rp 0</span>
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Untuk Bayar') }}</label>
                            <p class="mb-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Maksimal') }}: <span class="font-semibold" x-text="fmt2(maxBayar)">Rp 0</span></p>
                            <input type="number" name="bayar_nominal" x-model.number="bayarNominal" :max="maxBayar" value="{{ old('bayar_nominal', 0) }}" step="1" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" />
                            @error('bayar_nominal')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Untuk Simpan') }}</label>
                            <p class="mb-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Maksimal') }}: <span class="font-semibold" x-text="fmt2(maxSimpan)">Rp 0</span></p>
                            <input type="number" name="simpan_nominal" x-model.number="simpanNominal" :max="maxSimpan" value="{{ old('simpan_nominal', 0) }}" step="1" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" />
                            @error('simpan_nominal')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div hidden>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Untuk Ambil') }}</label>
                            <p class="mb-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Maksimal') }}: <span class="font-semibold" x-text="fmt2(maxAmbil)">Rp 0</span></p>
                            <input type="number" name="ambil_nominal" x-model.number="ambilNominal" :max="maxAmbil" value="{{ old('ambil_nominal', 0) }}" step="1" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" />
                            @error('ambil_nominal')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-3 text-sm">
                        <div class="text-gray-600 dark:text-gray-400">
                            {{ __('Total Alokasi') }}: <span class="font-semibold text-gray-900 dark:text-gray-100" x-text="fmt(totalAlokasi)">Rp 0</span>
                        </div>
                        <div class="mt-1" :class="sisaAlokasi === 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                            {{ __('Sisa dari Harga Akhir') }}: <span class="font-semibold" x-text="fmt(sisaAlokasi)"></span>
                        </div>
                    </div>
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

                <input type="hidden" name="satuan" :value="satuan">
                <input type="hidden" name="berat_setelah_potong" :value="beratSetelahPotong">
                <input type="hidden" name="biaya_admin" :value="biayaAdmin">
                <input type="hidden" name="harga_akhir" :value="hargaAkhir">

                <div class="flex gap-3">
                    <x-button type="primary" x-bind:disabled="formSubmitted || sisaAlokasi !== 0" x-text="formSubmitted ? '{{ __('Menyimpan...') }}' : (sisaAlokasi !== 0 ? '{{ __('Sisa Harus 0') }}' : '{{ __('Simpan') }}')"></x-button>
                    <a href="{{ route('pembelian.index') }}">
                        <x-button type="secondary">{{ __('Batal') }}</x-button>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        new TomSelect('#nasabah_id', {
            create: false,
            sortField: {
                field: 'text',
                direction: 'asc'
            },
            placeholder: '{{ __("Pilih Nasabah") }}'
        });

        document.getElementById('pembelian-form').addEventListener('submit', function(e) {
            const printCheckbox = document.getElementById('print_invoice');

            if (printCheckbox.checked) {
                e.preventDefault();

                fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this),
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        window.open(data.print_url, '_blank');
                        setTimeout(() => {
                            window.location.href = data.redirect_url;
                        }, 500);
                    })
                    .catch(() => alert('Terjadi kesalahan saat menyimpan data'));
            }
        });
    </script>
</x-layouts.app>