<x-layouts.app>
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <x-icons.chevron-right />
        <a href="{{ route('permissions.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Permissions') }}</a>
        <x-icons.chevron-right />
        <span class="text-gray-500 dark:text-gray-400">{{ __('Edit') }}</span>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Permission') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Update permission details') }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <form action="{{ route('permissions.update', $permission) }}" method="POST" class="max-w-md">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <x-forms.input label="Name" name="name" type="text" value="{{ old('name', $permission->name) }}" required />
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('e.g., create-posts, edit-users, delete-comments') }}</p>
                </div>

                <div class="flex gap-3">
                    <x-button type="primary">{{ __('Update') }}</x-button>
                    <a href="{{ route('permissions.index') }}">
                        <x-button type="secondary">{{ __('Cancel') }}</x-button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
