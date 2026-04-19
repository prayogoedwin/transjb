<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <x-icons.chevron-right />
        <a href="{{ route('nasabah.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Nasabah') }}</a>
        <x-icons.chevron-right />
        <span class="text-gray-500 dark:text-gray-400">{{ __('Edit') }}</span>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Nasabah') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Perbarui data nasabah') }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden max-w-xl">
        <div class="p-6">
            <form action="{{ route('nasabah.update', $nasabah) }}" method="POST" class="max-w-2xl">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <x-forms.input label="Nama" name="nama" type="text" value="{{ old('nama', $nasabah->nama) }}" required />
                </div>

                <div class="mb-4">
                    <x-forms.input label="No. Telepon" name="no_telp" type="text" value="{{ old('no_telp', $nasabah->no_telp) }}" />
                </div>
<!-- 
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('User') }}</label>
                    <select name="user_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                        <option value="">{{ __('Pilih User') }}</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $nasabah->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div> -->

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Alamat') }}</label>
                    <textarea name="alamat" rows="4" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">{{ old('alamat', $nasabah->alamat) }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <x-button type="primary">{{ __('Perbarui') }}</x-button>
                    <x-button type="secondary" tag="a" href="{{ route('nasabah.index') }}">{{ __('Batal') }}</x-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
