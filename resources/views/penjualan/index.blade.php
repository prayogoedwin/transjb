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
            $('#penjualan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('penjualan.index') }}",
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
        });
    </script>
</x-layouts.app>