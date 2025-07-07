<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - E-Shuttle UNNES</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
        .glass-effect { backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.1); }
        .unnes-gold { color: #FFD700; }
        .unnes-blue { color: #1E40AF; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-gray-900 dark:via-blue-900 dark:to-purple-900 min-h-screen">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 bg-white/95 dark:bg-gray-800/95 backdrop-blur-lg shadow-2xl w-64 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-30 border-r border-gray-200/50 dark:border-gray-700/50">
            <div class="flex flex-col items-center justify-center h-20 px-4 border-b border-gray-200/50 dark:border-gray-700/50 gradient-bg">
                <!-- UNNES Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg">
                        <svg viewBox="0 0 100 100" class="w-8 h-8">
                            <defs>
                                <linearGradient id="unnesGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#FFD700;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#FFA500;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <!-- Simplified UNNES Logo -->
                            <circle cx="50" cy="50" r="45" fill="url(#unnesGradient)"/>
                            <path d="M30 25 Q50 15 70 25 Q65 35 50 40 Q35 35 30 25" fill="#1E40AF"/>
                            <path d="M25 35 Q50 25 75 35 Q70 45 50 50 Q30 45 25 35" fill="#1E40AF"/>
                            <path d="M20 45 Q50 35 80 45 Q75 55 50 60 Q25 55 20 45" fill="#1E40AF"/>
                            <circle cx="50" cy="70" r="15" fill="#1E40AF"/>
                        </svg>
                    </div>
                    <div class="text-white">
                        <h1 class="text-lg font-bold leading-tight">E-Shuttle</h1>
                        <p class="text-xs opacity-90">UNNES Admin</p>
                    </div>
                </div>
            </div>
            <nav class="flex-1 overflow-y-auto py-6">
                <ul class="space-y-3 px-4">
                    <li>
                        <a href="#" onclick="showDashboard()" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-blue-500 hover:to-purple-600 hover:text-white rounded-xl group transition-all duration-300 transform hover:scale-105">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg mr-3 group-hover:bg-white/20 transition-colors duration-300">
                                <i data-feather="home" class="w-5 h-5 text-blue-600 dark:text-blue-400 group-hover:text-white"></i>
                            </div>
                            <span class="font-medium">Beranda</span>
                        </a>
                    </li>
                    <li>
                        <div class="relative">
                            <button onclick="toggleSubmenu('dataSubmenu')" class="w-full flex items-center justify-between px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-indigo-500 hover:to-purple-600 hover:text-white rounded-xl group transition-all duration-300 transform hover:scale-105">
                                <div class="flex items-center">
                                    <div class="p-2 bg-indigo-100 dark:bg-indigo-900 rounded-lg mr-3 group-hover:bg-white/20 transition-colors duration-300">
                                        <i data-feather="database" class="w-5 h-5 text-indigo-600 dark:text-indigo-400 group-hover:text-white"></i>
                                    </div>
                                    <span class="font-medium">Kelola Data</span>
                                </div>
                                <i data-feather="chevron-down" class="w-4 h-4 transform transition-transform duration-300 group-hover:text-white" id="dataSubmenuIcon"></i>
                            </button>
                            <ul id="dataSubmenu" class="hidden mt-3 ml-6 space-y-2">
                                <li>
                                    <button onclick="showDataManagement('users')" class="w-full text-left px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gradient-to-r hover:from-blue-400 hover:to-blue-600 hover:text-white rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                                        <i data-feather="users" class="w-4 h-4 inline mr-3"></i>
                                        <span class="font-medium">Users</span>
                                    </button>
                                </li>
                                <li>
                                    <button onclick="showDataManagement('kerumunan')" class="w-full text-left px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gradient-to-r hover:from-green-400 hover:to-green-600 hover:text-white rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                                        <i data-feather="activity" class="w-4 h-4 inline mr-3"></i>
                                        <span class="font-medium">Data Kerumunan</span>
                                    </button>
                                </li>
                                <li>
                                    <button onclick="showDataManagement('halte')" class="w-full text-left px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gradient-to-r hover:from-purple-400 hover:to-purple-600 hover:text-white rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                                        <i data-feather="map-pin" class="w-4 h-4 inline mr-3"></i>
                                        <span class="font-medium">Data Halte</span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="{{ route('admin.map') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-green-500 hover:to-teal-600 hover:text-white rounded-xl group transition-all duration-300 transform hover:scale-105">
                            <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg mr-3 group-hover:bg-white/20 transition-colors duration-300">
                                <i data-feather="map" class="w-5 h-5 text-green-600 dark:text-green-400 group-hover:text-white"></i>
                            </div>
                            <span class="font-medium">Peta CCTV</span>
                        </a>
                    </li>
                    <li class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-red-500 hover:to-pink-600 hover:text-white rounded-xl group transition-all duration-300 transform hover:scale-105">
                                <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg mr-3 group-hover:bg-white/20 transition-colors duration-300">
                                    <i data-feather="log-out" class="w-5 h-5 text-red-600 dark:text-red-400 group-hover:text-white"></i>
                                </div>
                                <span class="font-medium">Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Overlay for mobile -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden md:hidden" onclick="toggleSidebar()"></div>

        <!-- Main Content -->
        <div class="flex-1 md:ml-64">
            <!-- Top Bar -->
            <header class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg shadow-lg border-b border-gray-200/50 dark:border-gray-700/50">
                <div class="flex items-center justify-between h-20 px-6">
                    <div class="flex items-center space-x-4">
                        <button class="md:hidden p-2 rounded-xl text-gray-600 dark:text-gray-400 hover:bg-gradient-to-r hover:from-blue-500 hover:to-purple-600 hover:text-white transition-all duration-300" onclick="toggleSidebar()">
                            <i data-feather="menu" class="w-6 h-6"></i>
                        </button>
                        <!-- Logo UNNES -->
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg viewBox="0 0 100 100" class="w-8 h-8 text-white">
                                    <path d="M50 10 L60 30 L50 25 L40 30 Z" fill="currentColor"/>
                                    <path d="M30 20 C30 20, 35 35, 50 40 C65 35, 70 20, 70 20 C70 30, 65 45, 50 50 C35 45, 30 30, 30 20" fill="currentColor"/>
                                    <path d="M25 30 C25 30, 30 45, 50 55 C70 45, 75 30, 75 30 C75 40, 70 55, 50 65 C30 55, 25 40, 25 30" fill="currentColor"/>
                                    <path d="M20 40 C20 40, 25 55, 50 70 C75 55, 80 40, 80 40 C80 50, 75 65, 50 80 C25 65, 20 50, 20 40" fill="currentColor"/>
                                    <circle cx="50" cy="85" r="8" fill="currentColor"/>
                                </svg>
                            </div>
                            <div>
                                <h1 id="pageTitle" class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Dashboard</h1>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">E-Shuttle Management System</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Administrator</p>
                        </div>
                        <div class="relative">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg ring-2 ring-blue-200 dark:ring-blue-800">
                                <span class="text-white text-sm font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white dark:border-gray-800"></div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="p-6">
                <!-- Dashboard Home -->
                <div id="dashboardHome">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        <!-- Statistics Cards -->
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg rounded-2xl shadow-xl p-6 border border-gray-200/50 dark:border-gray-700/50 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Total Users</h2>
                                    <p class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mt-2">{{ $stats['total_users'] }}</p>
                                    <div class="flex items-center mt-2">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Semua pengguna terdaftar</span>
                                    </div>
                                </div>
                                <div class="p-4 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl shadow-lg">
                                    <i data-feather="users" class="w-8 h-8 text-white"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg rounded-2xl shadow-xl p-6 border border-gray-200/50 dark:border-gray-700/50 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Regular Users</h2>
                                    <p class="text-3xl font-bold bg-gradient-to-r from-green-600 to-teal-600 bg-clip-text text-transparent mt-2">{{ $stats['active_users'] }}</p>
                                    <div class="flex items-center mt-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Pengguna aktif</span>
                                    </div>
                                </div>
                                <div class="p-4 bg-gradient-to-br from-green-500 to-teal-600 rounded-2xl shadow-lg">
                                    <i data-feather="user-check" class="w-8 h-8 text-white"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg rounded-2xl shadow-xl p-6 border border-gray-200/50 dark:border-gray-700/50 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Admin Users</h2>
                                    <p class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mt-2">{{ $stats['admin_users'] }}</p>
                                    <div class="flex items-center mt-2">
                                        <div class="w-2 h-2 bg-purple-500 rounded-full mr-2"></div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Administrator sistem</span>
                                    </div>
                                </div>
                                <div class="p-4 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl shadow-lg">
                                    <i data-feather="shield" class="w-8 h-8 text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg rounded-2xl shadow-xl p-8 border border-gray-200/50 dark:border-gray-700/50">
                        <div class="flex items-center mb-6">
                            <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg mr-3">
                                <i data-feather="zap" class="w-6 h-6 text-white"></i>
                            </div>
                            <h3 class="text-2xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">Aksi Cepat</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <button onclick="showDataManagement('users')" class="group relative overflow-hidden bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-2xl p-6 hover:shadow-2xl transition-all duration-300 transform hover:scale-105 border border-blue-200/50 dark:border-blue-700/50">
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-purple-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative z-10">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="p-3 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                                            <i data-feather="users" class="w-6 h-6 text-white"></i>
                                        </div>
                                        <i data-feather="arrow-right" class="w-5 h-5 text-blue-500 group-hover:text-purple-600 transition-colors duration-300"></i>
                                    </div>
                                    <h4 class="font-bold text-gray-900 dark:text-white text-lg mb-2">Kelola Users</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Tambah, edit, hapus users sistem</p>
                                </div>
                            </button>
                            <button onclick="showDataManagement('kerumunan')" class="group relative overflow-hidden bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-800/30 rounded-2xl p-6 hover:shadow-2xl transition-all duration-300 transform hover:scale-105 border border-green-200/50 dark:border-green-700/50">
                                <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-teal-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative z-10">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="p-3 bg-gradient-to-br from-green-500 to-teal-600 rounded-xl shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                                            <i data-feather="activity" class="w-6 h-6 text-white"></i>
                                        </div>
                                        <i data-feather="arrow-right" class="w-5 h-5 text-green-500 group-hover:text-teal-600 transition-colors duration-300"></i>
                                    </div>
                                    <h4 class="font-bold text-gray-900 dark:text-white text-lg mb-2">Data Kerumunan</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Monitor kerumunan halte real-time</p>
                                </div>
                            </button>
                            <button onclick="showDataManagement('halte')" class="group relative overflow-hidden bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-800/30 rounded-2xl p-6 hover:shadow-2xl transition-all duration-300 transform hover:scale-105 border border-purple-200/50 dark:border-purple-700/50">
                                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-pink-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative z-10">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="p-3 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                                            <i data-feather="map-pin" class="w-6 h-6 text-white"></i>
                                        </div>
                                        <i data-feather="arrow-right" class="w-5 h-5 text-purple-500 group-hover:text-pink-600 transition-colors duration-300"></i>
                                    </div>
                                    <h4 class="font-bold text-gray-900 dark:text-white text-lg mb-2">Data Halte</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Kelola lokasi dan info halte</p>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Data Management Section -->
                <div id="dataManagement" class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 hidden">
                    <div class="p-8">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
                            <div class="flex items-center mb-4 sm:mb-0">
                                <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg mr-3">
                                    <i data-feather="database" class="w-6 h-6 text-white"></i>
                                </div>
                                <h2 id="dataTitle" class="text-2xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">Manajemen Data</h2>
                            </div>
                            <a id="addNewBtn" href="{{ route('admin.users.create') }}" class="group relative overflow-hidden px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 flex items-center gap-3 w-fit">
                                <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-green-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <i data-feather="plus" class="w-5 h-5 relative z-10"></i>
                                <span class="font-semibold relative z-10">Tambah Data</span>
                            </a>
                        </div>

                        <!-- Loading Indicator -->
                        <div id="loadingIndicator" class="hidden flex items-center justify-center py-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                            <span class="ml-2 text-gray-600 dark:text-gray-400">Memuat data...</span>
                        </div>

                        <!-- Error Message -->
                        <div id="errorMessage" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <span id="errorText"></span>
                        </div>

                        <!-- Data Table -->
                        <div class="overflow-x-auto rounded-2xl border border-gray-200/50 dark:border-gray-700/50">
                            <table class="min-w-full divide-y divide-gray-200/50 dark:divide-gray-700/50">
                                <thead id="tableHeader" class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                                    <!-- Headers will be dynamically generated -->
                                </thead>
                                <tbody id="tableBody" class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm divide-y divide-gray-200/30 dark:divide-gray-700/30">
                                    <!-- Data will be loaded via JavaScript -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div id="pagination" class="flex flex-col sm:flex-row items-center justify-between mt-8 p-4 bg-gray-50/50 dark:bg-gray-700/50 rounded-xl backdrop-blur-sm">
                            <div class="text-sm text-gray-700 dark:text-gray-300 font-medium mb-4 sm:mb-0">
                                Menampilkan <span id="showingFrom" class="font-bold text-blue-600 dark:text-blue-400">1</span> sampai <span id="showingTo" class="font-bold text-blue-600 dark:text-blue-400">10</span> dari <span id="totalEntries" class="font-bold text-blue-600 dark:text-blue-400">0</span> entri
                            </div>
                            <div class="flex items-center space-x-2">
                                <button id="prevBtn" class="px-4 py-2 text-sm bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gradient-to-r hover:from-blue-500 hover:to-purple-600 hover:text-white transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed border border-gray-200 dark:border-gray-600 shadow-sm" disabled>
                                    <i data-feather="chevron-left" class="w-4 h-4 inline mr-1"></i>
                                    Sebelumnya
                                </button>
                                <div id="pageNumbers" class="flex space-x-1">
                                    <!-- Page numbers will be generated by JavaScript -->
                                </div>
                                <button id="nextBtn" class="px-4 py-2 text-sm bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gradient-to-r hover:from-blue-500 hover:to-purple-600 hover:text-white transition-all duration-300 border border-gray-200 dark:border-gray-600 shadow-sm">
                                    Selanjutnya
                                    <i data-feather="chevron-right" class="w-4 h-4 inline ml-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal untuk Tambah/Edit Data -->
    <div id="dataModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 mx-auto p-6 w-11/12 md:w-3/4 lg:w-1/2 max-w-4xl">
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-gray-200/50 dark:border-gray-700/50">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl mr-3">
                                <i data-feather="edit" class="w-6 h-6 text-white"></i>
                            </div>
                            <h3 id="modalTitle" class="text-2xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">Tambah Data</h3>
                        </div>
                        <button onclick="closeModal()" class="p-2 text-gray-400 hover:text-white hover:bg-gradient-to-r hover:from-red-500 hover:to-pink-600 rounded-xl transition-all duration-300">
                            <i data-feather="x" class="w-6 h-6"></i>
                        </button>
                    </div>
                <form id="dataForm">
                    <div id="formFields"></div>
                    <!-- Map Container for Halte -->
                    <div id="mapContainer" class="hidden mt-6">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Pilih Lokasi Halte</label>
                        <div id="map" class="w-full h-64 rounded-2xl border-2 border-gray-300/50 dark:border-gray-600/50 shadow-lg"></div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 flex items-center">
                            <i data-feather="info" class="w-3 h-3 mr-1"></i>
                            Klik pada peta untuk memilih lokasi halte
                        </p>
                    </div>
                    <div class="flex justify-end space-x-4 mt-8">
                        <button type="button" onclick="closeModal()" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gradient-to-r hover:from-gray-400 hover:to-gray-500 hover:text-white transition-all duration-300 font-medium">
                            <i data-feather="x" class="w-4 h-4 inline mr-2"></i>
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 font-medium">
                            <i data-feather="save" class="w-4 h-4 inline mr-2"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-6 w-96 max-w-md">
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-gray-200/50 dark:border-gray-700/50">
                <div class="p-8 text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900/50 dark:to-red-800/50 mb-6">
                        <i data-feather="alert-triangle" class="w-8 h-8 text-red-600 dark:text-red-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Konfirmasi Hapus</h3>
                    <div class="mb-6">
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="flex justify-center space-x-4">
                        <button onclick="closeConfirmModal()" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gradient-to-r hover:from-gray-400 hover:to-gray-500 hover:text-white transition-all duration-300 font-medium">
                            <i data-feather="x" class="w-4 h-4 inline mr-2"></i>
                            Batal
                        </button>
                        <button onclick="performDelete()" class="px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 font-medium">
                            <i data-feather="trash-2" class="w-4 h-4 inline mr-2"></i>
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Toast -->
    <div id="toast" class="fixed top-4 right-4 p-4 rounded-lg shadow-lg hidden z-50 transition-all duration-300">
        <div class="flex items-center">
            <div id="toastIcon" class="flex-shrink-0 w-5 h-5 mr-3"></div>
            <div id="toastMessage" class="text-sm font-medium"></div>
        </div>
    </div>

    <script>
        // Global variables
        let currentTab = 'users';
        let currentPage = 1;
        let itemsPerPage = 10;
        let allData = {
            users: @json($users),
            kerumunan: [],
            halte: []
        };
        let filteredData = {};
        let editingItem = null;
        let editingType = null;
        let map = null;
        let marker = null;
        let currentView = 'dashboard'; // 'dashboard' or 'dataManagement'
        let deleteItemId = null;

        // Initialize the dashboard
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
            loadExternalData();
            showDashboard(); // Show dashboard by default
        });

        // Navigation functions
        function showDashboard() {
            currentView = 'dashboard';
            document.getElementById('dashboardHome').classList.remove('hidden');
            document.getElementById('dataManagement').classList.add('hidden');
            document.getElementById('pageTitle').textContent = 'Dashboard';
            
            // Close sidebar on mobile
            if (window.innerWidth < 768) {
                closeSidebar();
            }
        }

        function showDataManagement(tab) {
            currentView = 'dataManagement';
            currentTab = tab;
            document.getElementById('dashboardHome').classList.add('hidden');
            document.getElementById('dataManagement').classList.remove('hidden');
            
            // Update title based on tab
            const titles = {
                'users': 'Manajemen Users',
                'kerumunan': 'Manajemen Data Kerumunan', 
                'halte': 'Manajemen Data Halte'
            };
            document.getElementById('pageTitle').textContent = titles[tab];
            document.getElementById('dataTitle').textContent = titles[tab];
            
            updateTable();
            updateAddButton();
            
            // Close sidebar on mobile
            if (window.innerWidth < 768) {
                closeSidebar();
            }
        }

        function toggleSubmenu(submenuId) {
            const submenu = document.getElementById(submenuId);
            const icon = document.getElementById(submenuId + 'Icon');
            
            if (submenu.classList.contains('hidden')) {
                submenu.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                submenu.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        // Initialize map for halte form
        function initializeMap() {
            if (map) {
                map.remove();
            }
            
            // Default to Universitas Negeri Semarang coordinates
            const defaultLat = -7.0051;
            const defaultLng = 110.4381;
            
            map = L.map('map').setView([defaultLat, defaultLng], 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            // Add click event to map
            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;
                
                // Remove existing marker
                if (marker) {
                    map.removeLayer(marker);
                }
                
                // Add new marker
                marker = L.marker([lat, lng]).addTo(map);
                
                // Update form fields
                document.getElementById('latitude').value = lat.toFixed(6);
                document.getElementById('longitude').value = lng.toFixed(6);
            });
        }

        function updateAddButton() {
            const addBtn = document.getElementById('addNewBtn');
            
            if (currentTab === 'users') {
                addBtn.href = '/admin/users/create';
                addBtn.onclick = null;
            } else if (currentTab === 'halte') {
                addBtn.href = '/admin/halte/create';
                addBtn.onclick = null;
            } else {
                addBtn.href = '#';
                addBtn.onclick = function(e) {
                    e.preventDefault();
                    openModal('add', currentTab);
                };
            }
        }

        async function loadExternalData() {
            showLoading(true);
            hideError();

            try {
                // Load kerumunan data
                const kerumunanResponse = await fetch('/api/kerumunan');
                const kerumunanData = await kerumunanResponse.json();
                
                if (kerumunanData.success) {
                    allData.kerumunan = kerumunanData.data;
                }

                // Load halte data
                const halteResponse = await fetch('/api/halte');
                const halteData = await halteResponse.json();
                
                if (halteData.success) {
                    allData.halte = halteData.data;
                }

                if (currentView === 'dataManagement') {
                    updateTable();
                }
            } catch (error) {
                console.error('Error loading data:', error);
                showError('Gagal memuat data dari server. Pastikan backend Python berjalan.');
            } finally {
                showLoading(false);
            }
        }

        function updateTable() {
            const data = allData[currentTab] || [];
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const paginatedData = data.slice(startIndex, endIndex);

            renderTableHeader();
            renderTable(paginatedData);
            updatePagination(data.length);
            feather.replace();
        }

        function renderTableHeader() {
            const tableHeader = document.getElementById('tableHeader');
            let headerHTML = '';

            switch (currentTab) {
                case 'users':
                    headerHTML = `
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Dibuat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    `;
                    break;
                case 'kerumunan':
                    headerHTML = `
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID Kerumunan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID Halte</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah Kerumunan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    `;
                    break;
                case 'halte':
                    headerHTML = `
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID Halte</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Halte</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">CCTV URL</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Latitude</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Longitude</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    `;
                    break;
            }

            tableHeader.innerHTML = headerHTML;
        }

        function renderTable(data) {
            const tableBody = document.getElementById('tableBody');
            
            if (data.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            <i data-feather="inbox" class="w-8 h-8 mx-auto mb-2"></i>
                            <p>Tidak ada data yang ditemukan</p>
                        </td>
                    </tr>
                `;
                return;
            }

            let html = '';
            data.forEach(item => {
                html += renderTableRow(item);
            });
            
            tableBody.innerHTML = html;
        }

        function renderTableRow(item) {
            switch (currentTab) {
                case 'users':
                    return `
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">${item.id}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">${item.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">${item.email}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                    item.role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800'
                                }">
                                    ${item.role.charAt(0).toUpperCase() + item.role.slice(1)}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">${new Date(item.created_at).toLocaleDateString('id-ID')}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="/admin/users/${item.id}/edit" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3" title="Edit">
                                    <i data-feather="edit" class="w-4 h-4"></i>
                                </a>
                                <button onclick="openConfirmModal(${item.id})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Hapus">
                                    <i data-feather="trash-2" class="w-4 h-4"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                case 'kerumunan':
                    return `
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">${item.id_kerumunan || '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">${item.id_halte}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">${item.waktu}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                    item.jumlah_kerumunan > 10 ? 'bg-red-100 text-red-800' : 
                                    item.jumlah_kerumunan > 5 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'
                                }">
                                    ${item.jumlah_kerumunan} orang
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="openModal('edit', 'kerumunan', ${JSON.stringify(item).replace(/"/g, '&quot;')})" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3" title="Edit">
                                    <i data-feather="edit" class="w-4 h-4"></i>
                                </button>
                                <button onclick="openConfirmModal(${item.id_kerumunan})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Hapus">
                                    <i data-feather="trash-2" class="w-4 h-4"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                case 'halte':
                    return `
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">${item.id_halte || '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">${item.nama_halte}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <span class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 rounded">
                                    ${item.cctv_url ? 'Ada CCTV' : 'Tidak ada CCTV'}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">${item.latitude || '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">${item.longitude || '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="/admin/halte/${item.id_halte}/edit" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3" title="Edit">
                                    <i data-feather="edit" class="w-4 h-4"></i>
                                </a>
                                <button onclick="openConfirmModal(${item.id_halte})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Hapus">
                                    <i data-feather="trash-2" class="w-4 h-4"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                default:
                    return '';
            }
        }

        function openModal(action, type, item = null) {
            editingType = type;
            editingItem = item;
            
            const modal = document.getElementById('dataModal');
            const title = document.getElementById('modalTitle');
            const formFields = document.getElementById('formFields');
            
            title.textContent = action === 'add' ? `Tambah ${getTypeLabel(type)}` : `Edit ${getTypeLabel(type)}`;
            
            // Generate form fields based on type
            let fieldsHTML = '';
            
            if (type === 'kerumunan') {
                fieldsHTML = `
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ID Halte</label>
                        <input type="text" id="id_halte" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" value="${item ? item.id_halte : ''}" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Waktu</label>
                        <input type="datetime-local" id="waktu" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" value="${item ? item.waktu : ''}" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jumlah Kerumunan</label>
                        <input type="number" id="jumlah_kerumunan" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" value="${item ? item.jumlah_kerumunan : ''}" required>
                    </div>
                `;
            } else if (type === 'halte') {
                fieldsHTML = `
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Halte</label>
                        <input type="text" id="nama_halte" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" value="${item ? item.nama_halte : ''}" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">URL CCTV</label>
                        <input type="url" id="cctv_url" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" value="${item ? item.cctv_url : ''}" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Latitude</label>
                            <input type="number" step="any" id="latitude" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" value="${item ? item.latitude : ''}" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Longitude</label>
                            <input type="number" step="any" id="longitude" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" value="${item ? item.longitude : ''}" readonly>
                        </div>
                    </div>
                `;
            }
            
            formFields.innerHTML = fieldsHTML;
            
            // Show/hide map container
            const mapContainer = document.getElementById('mapContainer');
            if (type === 'halte') {
                mapContainer.classList.remove('hidden');
                // Initialize map after modal is shown
                setTimeout(() => {
                    initializeMap();
                    
                    // If editing, set marker position
                    if (item && item.latitude && item.longitude) {
                        const lat = parseFloat(item.latitude);
                        const lng = parseFloat(item.longitude);
                        map.setView([lat, lng], 15);
                        marker = L.marker([lat, lng]).addTo(map);
                    }
                }, 100);
            } else {
                mapContainer.classList.add('hidden');
            }
            
            modal.classList.remove('hidden');
        }

        function closeModal() {
            const modal = document.getElementById('dataModal');
            modal.classList.add('hidden');
            editingItem = null;
            editingType = null;
            
            // Clean up map
            if (map) {
                map.remove();
                map = null;
                marker = null;
            }
        }

        function openConfirmModal(itemId) {
            deleteItemId = itemId;
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('hidden');
        }

        function closeConfirmModal() {
            const modal = document.getElementById('confirmModal');
            modal.classList.add('hidden');
            deleteItemId = null;
        }

        async function performDelete() {
            try {
                let response;
                
                if (currentTab === 'users') {
                    response = await fetch(`/api/users/${deleteItemId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        }
                    });
                } else {
                    const endpoints = {
                        'kerumunan': '/api/kerumunan',
                        'halte': '/api/halte'
                    };
                    
                    response = await fetch(`${endpoints[currentTab]}/${deleteItemId}`, {
                        method: 'DELETE'
                    });
                }
                
                const result = await response.json();
                
                if (response.ok && (result.success || result.message)) {
                    showToast('success', 'Data berhasil dihapus!');
                    closeConfirmModal();
                    
                    if (currentTab === 'users') {
                        window.location.reload();
                    } else {
                        await loadExternalData();
                    }
                } else {
                    throw new Error(result.message || 'Gagal menghapus data');
                }
            } catch (error) {
                console.error('Error deleting data:', error);
                showToast('error', 'Gagal menghapus data: ' + error.message);
            }
        }

        // Form submission
        document.getElementById('dataForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {};
            
            // Collect form data
            if (editingType === 'kerumunan') {
                data.id_halte = document.getElementById('id_halte').value;
                data.waktu = document.getElementById('waktu').value;
                data.jumlah_kerumunan = parseInt(document.getElementById('jumlah_kerumunan').value);
            } else if (editingType === 'halte') {
                data.nama_halte = document.getElementById('nama_halte').value;
                data.cctv_url = document.getElementById('cctv_url').value;
                data.latitude = parseFloat(document.getElementById('latitude').value);
                data.longitude = parseFloat(document.getElementById('longitude').value);
            }
            
            try {
                let response;
                const endpoints = {
                    'kerumunan': '/api/kerumunan',
                    'halte': '/api/halte'
                };
                
                if (editingItem) {
                    // Update existing item
                    const itemId = editingType === 'kerumunan' ? editingItem.id_kerumunan : editingItem.id_halte;
                    response = await fetch(`${endpoints[editingType]}/${itemId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });
                } else {
                    // Create new item
                    response = await fetch(endpoints[editingType], {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });
                }
                
                const result = await response.json();
                
                if (response.ok && result.success) {
                    showToast('success', editingItem ? 'Data berhasil diupdate!' : 'Data berhasil ditambahkan!');
                    closeModal();
                    await loadExternalData();
                } else {
                    throw new Error(result.message || 'Gagal menyimpan data');
                }
            } catch (error) {
                console.error('Error saving data:', error);
                showToast('error', 'Gagal menyimpan data: ' + error.message);
            }
        });

        function updatePagination(totalItems) {
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            const startItem = (currentPage - 1) * itemsPerPage + 1;
            const endItem = Math.min(currentPage * itemsPerPage, totalItems);

            // Update pagination info
            document.getElementById('showingFrom').textContent = totalItems > 0 ? startItem : 0;
            document.getElementById('showingTo').textContent = endItem;
            document.getElementById('totalEntries').textContent = totalItems;

            // Update pagination buttons
            document.getElementById('prevBtn').disabled = currentPage <= 1;
            document.getElementById('nextBtn').disabled = currentPage >= totalPages;

            // Generate page numbers
            generatePageNumbers(totalPages);
        }

        function generatePageNumbers(totalPages) {
            const pageNumbersContainer = document.getElementById('pageNumbers');
            let html = '';

            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            for (let i = startPage; i <= endPage; i++) {
                html += `
                    <button class="px-3 py-1 text-sm rounded ${
                        i === currentPage 
                            ? 'bg-blue-600 text-white' 
                            : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600'
                    }" onclick="goToPage(${i})">
                        ${i}
                    </button>
                `;
            }

            pageNumbersContainer.innerHTML = html;
        }

        function goToPage(page) {
            currentPage = page;
            updateTable();
        }

        // Pagination event listeners
        document.getElementById('prevBtn').addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                updateTable();
            }
        });

        document.getElementById('nextBtn').addEventListener('click', () => {
            const totalPages = Math.ceil((allData[currentTab] || []).length / itemsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                updateTable();
            }
        });

        function showLoading(show) {
            const loadingIndicator = document.getElementById('loadingIndicator');
            if (show) {
                loadingIndicator.classList.remove('hidden');
            } else {
                loadingIndicator.classList.add('hidden');
            }
        }

        function showError(message) {
            const errorMessage = document.getElementById('errorMessage');
            const errorText = document.getElementById('errorText');
            errorText.textContent = message;
            errorMessage.classList.remove('hidden');
        }

        function hideError() {
            const errorMessage = document.getElementById('errorMessage');
            errorMessage.classList.add('hidden');
        }

        function getTypeLabel(type) {
            const labels = {
                'users': 'User',
                'kerumunan': 'Data Kerumunan',
                'halte': 'Data Halte'
            };
            return labels[type] || 'Data';
        }

        function showToast(type, message) {
            const toast = document.getElementById('toast');
            const toastIcon = document.getElementById('toastIcon');
            const toastMessage = document.getElementById('toastMessage');
            
            // Reset classes
            toast.className = 'fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transition-all duration-300';
            
            if (type === 'success') {
                toast.classList.add('bg-green-100', 'border', 'border-green-400', 'text-green-700');
                toastIcon.innerHTML = '<i data-feather="check-circle" class="w-5 h-5 text-green-600"></i>';
            } else {
                toast.classList.add('bg-red-100', 'border', 'border-red-400', 'text-red-700');
                toastIcon.innerHTML = '<i data-feather="x-circle" class="w-5 h-5 text-red-600"></i>';
            }
            
            toastMessage.textContent = message;
            toast.classList.remove('hidden');
            feather.replace();
            
            // Auto hide after 3 seconds
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }
    </script>
</body>
</html>