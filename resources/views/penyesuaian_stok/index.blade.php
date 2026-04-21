<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Penyesuaian Stok') }}</span>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Penyesuaian Stok') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Tambah atau kurangi stok produk secara manual') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('penyesuaian_stok.create') }}">
                <x-button type="primary">{{ __('Tambah Penyesuaian') }}</x-button>
            </a>
        </div>
    </div>

    @if(session('status'))
        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 rounded-lg">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-4">
            <table id="penyesuaian-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Produk') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Jumlah') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Oleh') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Tanggal') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#penyesuaian-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('penyesuaian_stok.index') }}',
                columns: [
                    { data: 'produk', name: 'produk' },
                    { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                    { data: 'jumlah_satuan', name: 'jumlah', orderable: false, searchable: false },
                    { data: 'oleh', name: 'oleh', orderable: false, searchable: false },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-right whitespace-nowrap' }
                ],
                order: [[4, 'desc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    zeroRecords: "Tidak ada data yang cocok",
                    emptyTable: "Tidak ada data tersedia"
                },
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"lf>rt<"flex flex-col md:flex-row justify-between items-center mt-4"ip>',
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                stripeClasses: ['bg-white dark:bg-gray-800', 'bg-gray-50 dark:bg-gray-900']
            });
        });
    </script>

    <style>
        #penyesuaian-table { border-collapse: separate !important; border-spacing: 0; }
        #penyesuaian-table thead th { border-bottom: 2px solid #e5e7eb; background-color: #f9fafb; }
        .dark #penyesuaian-table thead th { border-bottom-color: #374151; background-color: #1f2937; }
        #penyesuaian-table tbody tr { border-bottom: 1px solid #e5e7eb; }
        .dark #penyesuaian-table tbody tr { border-bottom-color: #374151; }
        #penyesuaian-table tbody tr.odd { background-color: #ffffff; }
        #penyesuaian-table tbody tr.even { background-color: #f9fafb; }
        .dark #penyesuaian-table tbody tr.odd { background-color: #1f2937; }
        .dark #penyesuaian-table tbody tr.even { background-color: #111827; }
        #penyesuaian-table tbody tr:hover { background-color: #e5e7eb !important; }
        .dark #penyesuaian-table tbody tr:hover { background-color: #374151 !important; }
        #penyesuaian-table tbody td { border-right: 1px solid #e5e7eb; padding: 12px 24px; }
        .dark #penyesuaian-table tbody td { border-right-color: #374151; }
        #penyesuaian-table tbody td:last-child { border-right: none; }
    </style>
</x-layouts.app>
