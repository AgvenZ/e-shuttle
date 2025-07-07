<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'E-Shuttle') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="antialiased bg-gradient-to-br from-blue-100 to-white dark:from-gray-900 dark:to-gray-800 dark:text-white min-h-screen flex items-center justify-center p-4">
    <div class="max-w-4xl w-full mx-auto text-center">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 md:p-12 space-y-8 transform hover:scale-105 transition-transform duration-300">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white tracking-tight">
                Welcome to <span class="text-blue-600 dark:text-blue-400">E-Shuttle</span>
            </h1>
            
            <p class="text-lg md:text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                Your modern transportation management solution. Choose your login type to continue.
            </p>

            <div class="grid md:grid-cols-2 gap-6 mt-8 max-w-2xl mx-auto">
                <a href="{{ route('admin.login') }}" 
                   class="group relative overflow-hidden rounded-xl bg-blue-600 dark:bg-blue-700 px-4 py-4 text-white shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="relative z-10">
                        <h3 class="text-xl font-semibold mb-1">Admin Login</h3>
                        <p class="text-blue-100">Access administrative controls</p>
                    </div>
                    <div class="absolute inset-0 z-0 bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-700 dark:to-blue-800 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </a>

                <a href="{{ route('user.login') }}"
                   class="group relative overflow-hidden rounded-xl bg-green-600 dark:bg-green-700 px-4 py-4 text-white shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="relative z-10">
                        <h3 class="text-xl font-semibold mb-1">User Login</h3>
                        <p class="text-green-100">Access your account</p>
                    </div>
                    <div class="absolute inset-0 z-0 bg-gradient-to-r from-green-600 to-green-700 dark:from-green-700 dark:to-green-800 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </a>
            </div>
        </div>

        <footer class="mt-8 text-gray-600 dark:text-gray-400">
            <p>&copy; {{ date('Y') }} E-Shuttle. All rights reserved.</p>
        </footer>
    </div>

    <div class="fixed bottom-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 via-green-500 to-blue-500"></div>
</body>
</html>
