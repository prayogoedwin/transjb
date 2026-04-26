<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <x-icons.chevron-right />
        <span class="text-gray-500 dark:text-gray-400">{{ __('Pembelian') }}</span>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Pembelian') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Kelola data pembelian') }}</p>
        </div>
        <div class="flex gap-2">
            @if(auth()->user()->hasPermission('create-pembelian'))
                <a href="{{ route('pembelian.create') }}">
                    <x-button type="primary">{{ __('Tambah Pembelian') }}</x-button>
                </a>
            @endif
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-4">
            <div class="mb-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                <div>
                    <label for="filter_date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Tanggal Mulai') }}</label>
                    <input type="date" id="filter_date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                </div>
                <div>
                    <label for="filter_date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Tanggal Akhir') }}</label>
                    <input type="date" id="filter_date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                </div>
                <div>
                    <label for="filter_nasabah" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Nama Nasabah') }}</label>
                    <select id="filter_nasabah" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                        <option value="">{{ __('Semua Nasabah') }}</option>
                        @foreach($nasabah as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <x-button type="primary" id="btn-filter-pembelian">{{ __('Cari') }}</x-button>
                    <x-button type="secondary" id="btn-reset-filter">{{ __('Reset') }}</x-button>
                </div>
                <div class="text-right">
                    <a id="btn-download-excel" href="{{ route('pembelian.export') }}">
                        <x-button type="secondary">{{ __('Download Excel') }}</x-button>
                    </a>
                </div>
            </div>

            <table id="pembelian-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Nasabah') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Produk') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Harga Satuan') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Total Berat') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Potongan') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Harga Akhir') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Tanggal') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <link rel="stylesheet" href="{{ asset('jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dataTables.tailwindcss.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css">
    <script src="{{ asset('jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <script>
        $(document).ready(function() {
            new TomSelect('#filter_nasabah', {
                create: false,
                sortField: { field: 'text', direction: 'asc' },
                placeholder: '{{ __("Semua Nasabah") }}'
            });

            const table = $('#pembelian-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pembelian.index') }}",
                    data: function(d) {
                        d.nasabah_id = $('#filter_nasabah').val();
                        d.date_from = $('#filter_date_from').val();
                        d.date_to = $('#filter_date_to').val();
                    }
                },
                columns: [
                    { data: 'nasabah_name', name: 'nasabah_name' },
                    { data: 'product_name', name: 'product_name' },
                    { data: 'harga_satuan_beli', name: 'harga_satuan_beli' },
                    { data: 'total_berat', name: 'total_berat' },
                    { data: 'potongan', name: 'potongan' },
                    { data: 'harga_akhir', name: 'harga_akhir' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-right whitespace-nowrap' }
                ],
                order: [[6, 'desc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari data...",
                    lengthMenu: "Tampilkan _MENU_",
                    info: "Menampilkan _START_ \\- _END_ dari _TOTAL_ pembelian",
                    infoEmpty: "Pembelian masih kosong",
                    infoFiltered: "(filter dari _MAX_ total pembelian)",
                    zeroRecords: "Pembelian tidak ditemukan",
                    emptyTable: "Pembelian tidak tersedia",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    }
                },
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"lf>rt<"flex flex-col md:flex-row justify-between items-center mt-4"ip>',
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                stripeClasses: ['bg-white dark:bg-gray-800', 'bg-gray-50 dark:bg-gray-900']
            });

            function updateExportUrl() {
                const params = new URLSearchParams();
                const nasabahId = $('#filter_nasabah').val();
                const dateFrom = $('#filter_date_from').val();
                const dateTo = $('#filter_date_to').val();

                if (nasabahId) params.set('nasabah_id', nasabahId);
                if (dateFrom) params.set('date_from', dateFrom);
                if (dateTo) params.set('date_to', dateTo);

                const baseUrl = "{{ route('pembelian.export') }}";
                $('#btn-download-excel').attr('href', params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl);
            }

            $('#btn-filter-pembelian').on('click', function() {
                table.draw();
                updateExportUrl();
            });

            $('#btn-reset-filter').on('click', function() {
                if (document.getElementById('filter_nasabah').tomselect) {
                    document.getElementById('filter_nasabah').tomselect.clear();
                } else {
                    $('#filter_nasabah').val('');
                }
                $('#filter_date_from').val('');
                $('#filter_date_to').val('');
                table.search('').draw();
                updateExportUrl();
            });

            $('#filter_nasabah, #filter_date_from, #filter_date_to').on('change', updateExportUrl);
            updateExportUrl();
        });
    </script>

    <style>
        #pembelian-table {
            border-collapse: separate !important;
            border-spacing: 0;
        }
        
        #pembelian-table thead th {
            border-bottom: 2px solid #e5e7eb;
            background-color: #f9fafb;
        }
    </style>
</x-layouts.app>
