<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <x-icons.chevron-right />
        <span class="text-gray-500 dark:text-gray-400">{{ __('Bayar & Hutang') }}</span>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Bayar & Hutang') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Kelola data Bayar & Hutang') }}</p>
        </div>
        <div class="flex gap-2">
            @if(auth()->user()->hasPermission('create-simpan-pinjam'))
                <a href="{{ route('simpan_pinjam.create') }}">
                    <x-button type="primary">{{ __('Tambah Catatan') }}</x-button>
                </a>
            @endif
        </div>
    </div>

    <!-- Filter dan Export Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
        <div class="p-4">
            <form method="GET" action="{{ route('simpan_pinjam.export') }}" class="flex gap-4 items-end flex-wrap">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Tanggal Mulai') }}</label>
                    <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" onchange="filterTable()" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Tanggal Akhir') }}</label>
                    <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" onchange="filterTable()" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                </div>
                <button type="button" onclick="resetFilter()" class="px-4 py-2 bg-gray-400 dark:bg-gray-600 text-white rounded-lg hover:bg-gray-500 dark:hover:bg-gray-700 transition">{{ __('Reset Filter') }}</button>
                <x-button type="secondary" class="mt-4">{{ __('Download Excel') }}</x-button>
            </form>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-4">
            <table id="simpan-pinjam-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Nasabah') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Tipe') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Nominal') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Tanggal Dibuat') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Aksi') }}</th>
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
        let table;
        
        $(document).ready(function() {
            table = $('#simpan-pinjam-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('simpan_pinjam.index') }}",
                    data: function(d) {
                        d.date_from = $('#date_from').val();
                        d.date_to = $('#date_to').val();
                    }
                },
                columns: [
                    { data: 'nasabah_name', name: 'nasabah_name' },
                    { data: 'tipe_badge', name: 'tipe' },
                    { data: 'nominal', name: 'nominal' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-right whitespace-nowrap' }
                ],
                order: [[3, 'desc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari data...",
                    lengthMenu: "Tampilkan _MENU_",
                    info: "Menampilkan _START_ \- _END_ dari _TOTAL_ catatan",
                    infoEmpty: "Catatan masih kosong",
                    infoFiltered: "(filter dari _MAX_ total catatan)",
                    zeroRecords: "Catatan tidak ditemukan",
                    emptyTable: "Catatan tidak tersedia",
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
        });

        function filterTable() {
            if (table) {
                table.ajax.reload();
            }
        }

        function resetFilter() {
            $('#date_from').val('');
            $('#date_to').val('');
            if (table) {
                table.ajax.reload();
            }
        }
    </script>

    <style>
        /* Table borders and styling */
        #simpan-pinjam-table {
            border-collapse: separate !important;
            border-spacing: 0;
        }
        
        #simpan-pinjam-table thead th {
            border-bottom: 2px solid #e5e7eb;
            background-color: #f9fafb;
        }
    </style>
</x-layouts.app>
