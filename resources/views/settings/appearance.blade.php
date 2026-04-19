<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <x-icons.chevron-right />
        <a href="{{ route('settings.profile.edit') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Settings') }}</a>
        <x-icons.chevron-right />
        <span class="text-gray-500 dark:text-gray-400">{{ __('Appearance') }}</span>
    </div>

    <!-- Page Title -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Appearance') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
            {{ __('Update the appearance settings for your account') }}
        </p>
    </div>

    <div class="p-6">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Sidebar Navigation -->
            @include('settings.partials.navigation')

            <!-- Profile Content -->
            <div class="flex-1">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                    <div class="p-6">
                        <!-- Theme Form -->
                        <form id="appearance-form" action="{{ route('settings.appearance.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="theme_preference" id="theme_preference"
                                value="{{ auth()->user()->theme_preference ?? 'system' }}">

                            <div class="mb-4">
                                <label for="theme"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Theme') }}</label>
                                <div class="inline-flex rounded-md shadow-sm" role="group">
                                    <button type="button" onclick="setAppearance('light')"
                                        class="px-4 py-2 text-sm font-medium border border-gray-200 rounded-l-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white {{ (auth()->user()->theme_preference ?? 'system') === 'light' ? 'bg-gray-100 text-blue-700 dark:bg-gray-600 dark:text-white' : 'bg-white text-gray-900 dark:bg-gray-700 dark:text-white' }}">
                                        {{ __('Light') }}
                                    </button>
                                    <button type="button" onclick="setAppearance('dark')"
                                        class="px-4 py-2 text-sm font-medium border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white {{ (auth()->user()->theme_preference ?? 'system') === 'dark' ? 'bg-gray-100 text-blue-700 dark:bg-gray-600 dark:text-white' : 'bg-white text-gray-900 dark:bg-gray-700 dark:text-white' }}">
                                        {{ __('Dark') }}
                                    </button>
                                    <button type="button" onclick="setAppearance('system')"
                                        class="px-4 py-2 text-sm font-medium border border-gray-200 rounded-r-md hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white {{ (auth()->user()->theme_preference ?? 'system') === 'system' ? 'bg-gray-100 text-blue-700 dark:bg-gray-600 dark:text-white' : 'bg-white text-gray-900 dark:bg-gray-700 dark:text-white' }}">
                                        {{ __('System') }}
                                    </button>
                                </div>
                            </div>
                        </form>

                        <script>
                            function setAppearance(theme) {
                                document.getElementById('theme_preference').value = theme;
                                
                                // Apply immediate visual feedback
                                if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                                    document.documentElement.classList.add('dark');
                                    localStorage.theme = 'dark';
                                } else {
                                    document.documentElement.classList.remove('dark');
                                    localStorage.theme = 'light';
                                }
                                
                                if (theme === 'system') {
                                    localStorage.removeItem('theme');
                                }

                                // Submit the form to persist in database
                                document.getElementById('appearance-form').submit();
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
