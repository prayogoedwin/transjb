<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name') }}</title>
    
    <!-- Dynamic Favicon -->
    @php
        $appName = config('app.name', 'App');
        $initials = collect(explode(' ', $appName))
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->take(3)
            ->implode('');
    @endphp
    <link rel="icon" type="image/svg+xml" href="{{ asset('minicon.svg') }}">
    
    <!-- Tailwind CSS CDN -->
    <script src="{{ asset('tailwind.js') }}"></script>
    
    <!-- Alpine.js CDN -->
    <script defer src="{{ asset('alpine.js') }}"></script>
    
    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>
    
    <script>
        function applyTheme() {
            const userPref = localStorage.getItem('darkMode');
            const systemPref = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (userPref === 'true' || (userPref === null && systemPref)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        // Initial theme application
        applyTheme();
        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!('darkMode' in localStorage)) {
                applyTheme();
            }
        });
    </script>
</head>

<body class="bg-gradient-to-br from-pink-100 via-purple-100 to-blue-100 dark:bg-gradient-to-br dark:from-pink-900 dark:via-purple-900 dark:to-blue-900 text-gray-800 dark:text-gray-200 antialiased" x-data="{
    darkMode: localStorage.getItem('darkMode') === 'true',
    toggleDarkMode() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode);
    }
}"
    :class="{ 'dark': darkMode }">

    <div class="min-h-screen flex flex-col">
        <!-- Main Content -->
        <main class="flex-1 flex items-center justify-center p-6">
            <div class="w-full max-w-md">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>

</html>
