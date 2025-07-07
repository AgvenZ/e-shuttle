<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - {{ config('app.name', 'E-Shuttle') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 bg-white dark:bg-gray-800 shadow-lg w-64 hidden md:flex md:flex-col transition-transform duration-300 ease-in-out transform">
            <div class="flex items-center justify-center h-16 px-4 border-b dark:border-gray-700">
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">E-Shuttle Admin</h1>
            </div>
            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-2 px-3">
                    <li>
                        <a href="#" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg group transition-colors duration-200">
                            <i data-feather="home" class="w-5 h-5 mr-3"></i>
                            <span>Beranda</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg group transition-colors duration-200">
                            <i data-feather="database" class="w-5 h-5 mr-3"></i>
                            <span>Kelola Data</span>
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg group transition-colors duration-200">
                                <i data-feather="log-out" class="w-5 h-5 mr-3"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 md:ml-64">
            <!-- Top Bar -->
            <header class="bg-white dark:bg-gray-800 shadow-sm">
                <div class="flex items-center justify-between h-16 px-4 border-b dark:border-gray-700">
                    <button class="md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700" onclick="toggleSidebar()">
                        <i data-feather="menu" class="w-6 h-6"></i>
                    </button>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700 dark:text-gray-300">Welcome, {{ auth()->user()->name }}</span>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Statistics Cards -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                                <i data-feather="users" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Users</h2>
                                <p class="text-2xl font-semibold text-gray-800 dark:text-white">1,234</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                                <i data-feather="activity" class="w-6 h-6 text-green-600 dark:text-green-400"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Sessions</h2>
                                <p class="text-2xl font-semibold text-gray-800 dark:text-white">42</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                                <i data-feather="clock" class="w-6 h-6 text-purple-600 dark:text-purple-400"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600 dark:text-gray-400">Uptime</h2>
                                <p class="text-2xl font-semibold text-gray-800 dark:text-white">99.9%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Recent Activity</h2>
                        <div class="space-y-4">
                            @for ($i = 0; $i < 5; $i++)
                            <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center">
                                        <i data-feather="bell" class="w-4 h-4 text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">New user registration</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">2 minutes ago</p>
                                </div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Initialize Feather Icons
        feather.replace();

        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.querySelector('aside');
            sidebar.classList.toggle('hidden');
        }
    </script>
</body>
</html>