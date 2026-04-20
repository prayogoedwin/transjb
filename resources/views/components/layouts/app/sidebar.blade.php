            <aside :class="{ 'w-full md:w-64': sidebarOpen, 'w-0 md:w-16 hidden md:block': !sidebarOpen }"
                class="bg-sidebar text-sidebar-foreground border-r border-gray-200 dark:border-gray-700 sidebar-transition overflow-hidden">
                <!-- Sidebar Content -->
                <div class="h-full flex flex-col">
                    <!-- Sidebar Menu -->
                    <nav class="flex-1 overflow-y-auto custom-scrollbar py-4">
                        <ul class="space-y-1 px-2">
                            <x-layouts.sidebar-link href="{{ route('dashboard') }}" icon='fas-house'
                                :active="request()->routeIs('dashboard*')">Dashboard</x-layouts.sidebar-link>

                                <x-layouts.sidebar-link href="{{ route('pembelian.index') }}" icon='fas-cart-shopping' :active="request()->routeIs('pembelian*')">Pembelian</x-layouts.sidebar-link>
                                <x-layouts.sidebar-link href="{{ route('penjualan.index') }}" icon='fas-money-bill-wave' :active="request()->routeIs('penjualan*')">Penjualan</x-layouts.sidebar-link>
                                <x-layouts.sidebar-link href="{{ route('simpan_pinjam.index') }}" icon='fas-piggy-bank' :active="request()->routeIs('simpan_pinjam*')">Bayar & Hutang</x-layouts.sidebar-link>
                                <x-layouts.sidebar-link href="{{ route('nasabah.index') }}" icon='fas-user' :active="request()->routeIs('nasabah*')">Nasabah</x-layouts.sidebar-link>
                                <x-layouts.sidebar-link href="{{ route('products.index') }}" icon='fas-box' :active="request()->routeIs('products*')">Produk</x-layouts.sidebar-link>
                            <x-layouts.sidebar-link href="{{ route('laporan.index') }}" icon='fas-chart-bar' :active="request()->routeIs('laporan*')">Laporan/Rekap</x-layouts.sidebar-link>

                            @if(auth()->user()->hasPermission('usermanagement-menu'))
                            <x-layouts.sidebar-two-level-link-parent title="User Management" icon="fas-users"
                                :active="request()->routeIs('users*') || request()->routeIs('roles*') || request()->routeIs('permissions*')">
                                <x-layouts.sidebar-two-level-link href="{{ route('users.index') }}" icon='fas-user'
                                    :active="request()->routeIs('users*')">Users</x-layouts.sidebar-two-level-link>
                                <x-layouts.sidebar-two-level-link href="{{ route('roles.index') }}" icon='fas-shield'
                                    :active="request()->routeIs('roles*')">Roles</x-layouts.sidebar-two-level-link>
                                <x-layouts.sidebar-two-level-link href="{{ route('permissions.index') }}" icon='fas-key'
                                    :active="request()->routeIs('permissions*')">Permissions</x-layouts.sidebar-two-level-link>
                            </x-layouts.sidebar-two-level-link-parent>
                            @endif
                        </ul>
                    </nav>
                </div>
            </aside>
