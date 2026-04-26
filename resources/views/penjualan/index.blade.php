<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <x-icons.chevron-right />
        <span class="text-gray-500 dark:text-gray-400">{{ __('Penjualan') }}</span>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Penjualan') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Kelola data penjualan') }}</p>
        </div>
        <div class="flex gap-2">
            @if(auth()->user()->hasPermission('create-penjualan'))
                <a href="{{ route('penjualan.create') }}">
                    <x-button type="primary">{{ __('Tambah Penjualan') }}</x-button>
                </a>
            @endif
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-4">
            <div class="mb-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                <div>
                    <label for="filter_date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Tanggal Mulai') }}</label>
                    <input type="date" id="filter_date_from" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                </div>
                <div>
                    <label for="filter_date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Tanggal Akhir') }}</label>
                    <input type="date" id="filter_date_to" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                </div>
                <div class="flex gap-2">
                    <x-button type="primary" id="btn-filter-penjualan">{{ __('Cari') }}</x-button>
                    <x-button type="secondary" id="btn-reset-filter">{{ __('Reset') }}</x-button>
                </div>
                <div class="text-right lg:col-start-5">
                    <a id="btn-download-excel" href="{{ route('penjualan.export') }}">
                        <x-button type="secondary">{{ __('Download Excel') }}</x-button>
                    </a>
                </div>
            </div>

            <table id="penjualan-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Pembeli') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Nopol') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Total Penjualan') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Items') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Tanggal') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <link rel="stylesheet" href="{{ asset('jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dataTables.tailwindcss.min.css') }}">
    <script src="{{ asset('jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('jquery.dataTables.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            const table = $('#penjualan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('penjualan.index') }}",
                    data: function(d) {
                        d.date_from = $('#filter_date_from').val();
                        d.date_to = $('#filter_date_to').val();
                    }
                },
                columns: [
                    { data: 'nama_customer', name: 'nama_customer' },
                    { data: 'nopol', name: 'nopol' },
                    { data: 'total_pembelian', name: 'total_pembelian' },
                    { data: 'items_count', name: 'items_count' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                ],
                order: [[4, 'desc']],
                pageLength: 10,
                language: {
                    lengthMenu: "_MENU_ per halaman",
                    zeroRecords: "{{ __('Tidak ada data penjualan') }}",
                    info: "{{ __('Menampilkan _START_ ke _END_ dari _TOTAL_ penjualan') }}",
                    infoEmpty: "{{ __('Tidak ada penjualan yang tersedia') }}",
                    infoFiltered: "{{ __('(disaring dari _MAX_ total penjualan)') }}",
                    search: "{{ __('Cari:') }}",
                    paginate: {
                        first: "{{ __('Pertama') }}",
                        last: "{{ __('Terakhir') }}",
                        next: "{{ __('Selanjutnya') }}",
                        previous: "{{ __('Sebelumnya') }}"
                    }
                }
            });

            function updateExportUrl() {
                const params = new URLSearchParams();
                const dateFrom = $('#filter_date_from').val();
                const dateTo = $('#filter_date_to').val();

                if (dateFrom) params.set('date_from', dateFrom);
                if (dateTo) params.set('date_to', dateTo);

                const baseUrl = "{{ route('penjualan.export') }}";
                $('#btn-download-excel').attr('href', params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl);
            }

            $('#btn-filter-penjualan').on('click', function() {
                table.draw();
                updateExportUrl();
            });

            $('#btn-reset-filter').on('click', function() {
                $('#filter_date_from').val('');
                $('#filter_date_to').val('');
                table.search('').draw();
                updateExportUrl();
            });

            $('#filter_date_from, #filter_date_to').on('change', updateExportUrl);
            updateExportUrl();
        });
    </script>
</x-layouts.app>