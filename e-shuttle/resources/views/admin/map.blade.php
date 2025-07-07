<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Lokasi CCTV - E-Shuttle Admin</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>

    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .leaflet-container {
            height: 450px;
            width: 100%;
            border-radius: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body class="min-h-screen gradient-bg">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="glass-effect shadow-lg border-b border-white/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-4">
                        <!-- Logo UNNES -->
                        <div class="w-10 h-10">
                            <svg viewBox="0 0 100 100" class="w-full h-full">
                                <circle cx="50" cy="50" r="45" fill="white" stroke="#667eea" stroke-width="2"/>
                                <text x="50" y="58" text-anchor="middle" fill="#667eea" font-size="20" font-weight="bold">U</text>
                            </svg>
                        </div>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 text-white/80 hover:text-white hover:scale-105 transition-all duration-300">
                            <i data-feather="arrow-left" class="w-5 h-5"></i>
                            <span>Kembali ke Dashboard</span>
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-white/80">{{ $user->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-red-300 hover:text-red-100 hover:scale-110 transition-all duration-300">
                                <i data-feather="log-out" class="w-5 h-5"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Floating Background Elements -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 left-10 w-20 h-20 bg-white/10 rounded-full floating-animation"></div>
            <div class="absolute top-40 right-20 w-16 h-16 bg-white/5 rounded-full floating-animation" style="animation-delay: -2s;"></div>
            <div class="absolute bottom-40 left-20 w-24 h-24 bg-white/5 rounded-full floating-animation" style="animation-delay: -4s;"></div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 relative">
            <!-- Header -->
            <div class="mb-8 text-center">
                <div class="flex items-center justify-center space-x-3 mb-4">
                    <i data-feather="map" class="w-8 h-8 text-white"></i>
                    <h1 class="text-4xl font-bold text-white">Manajemen Lokasi CCTV</h1>
                </div>
                <p class="text-lg text-white/80">Kelola lokasi CCTV di area Universitas Negeri Semarang</p>
            </div>

            <!-- Map and Statistics Container -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Map Section -->
                <div class="lg:col-span-2">
                    <div class="glass-effect rounded-3xl shadow-2xl border border-white/20 p-6">
                        <div class="flex items-center space-x-3 mb-6">
                            <i data-feather="map-pin" class="w-6 h-6 text-white"></i>
                            <h2 class="text-xl font-semibold text-white">Peta Lokasi UNNES</h2>
                        </div>
                        <div id="map" class="shadow-xl"></div>
                        <div class="flex items-center mt-4 text-sm text-white/70">
                            <i data-feather="info" class="w-4 h-4 mr-2"></i>
                            <span>Peta menampilkan lokasi CCTV yang tersedia di area kampus</span>
                        </div>
                    </div>
                </div>

                <!-- Statistics Section -->
                <div class="lg:col-span-1">
                    <div class="glass-effect rounded-3xl shadow-2xl border border-white/20 p-6">
                        <div class="flex items-center space-x-3 mb-6">
                            <i data-feather="bar-chart-3" class="w-6 h-6 text-white"></i>
                            <h2 class="text-xl font-semibold text-white">Statistik CCTV</h2>
                        </div>
                        
                        <!-- Total Locations -->
                        <div class="mb-4">
                            <div class="glass-effect p-4 rounded-xl border border-blue-300/30 hover:border-blue-300/50 transition-all duration-300">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full flex items-center justify-center">
                                            <i data-feather="map-pin" class="w-5 h-5 text-white"></i>
                                        </div>
                                        <span class="text-sm font-medium text-white">Total Lokasi</span>
                                    </div>
                                    <span id="totalLocations" class="text-2xl font-bold text-blue-300">0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Active CCTV -->
                        <div class="mb-4">
                            <div class="glass-effect p-4 rounded-xl border border-green-300/30 hover:border-green-300/50 transition-all duration-300">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-green-600 rounded-full flex items-center justify-center">
                                            <i data-feather="video" class="w-5 h-5 text-white"></i>
                                        </div>
                                        <span class="text-sm font-medium text-white">CCTV Aktif</span>
                                    </div>
                                    <span id="activeCCTV" class="text-2xl font-bold text-green-300">0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Inactive CCTV -->
                        <div class="mb-4">
                            <div class="glass-effect p-4 rounded-xl border border-red-300/30 hover:border-red-300/50 transition-all duration-300">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-r from-red-400 to-red-600 rounded-full flex items-center justify-center">
                                            <i data-feather="video-off" class="w-5 h-5 text-white"></i>
                                        </div>
                                        <span class="text-sm font-medium text-white">CCTV Tidak Aktif</span>
                                    </div>
                                    <span id="inactiveCCTV" class="text-2xl font-bold text-red-300">0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Coverage Area -->
                        <div class="mb-6">
                            <div class="glass-effect p-4 rounded-xl border border-purple-300/30 hover:border-purple-300/50 transition-all duration-300">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-r from-purple-400 to-purple-600 rounded-full flex items-center justify-center">
                                            <i data-feather="circle" class="w-5 h-5 text-white"></i>
                                        </div>
                                        <span class="text-sm font-medium text-white">Area Cakupan</span>
                                    </div>
                                    <span class="text-2xl font-bold text-purple-300">1 km</span>
                                </div>
                            </div>
                        </div>

                        <!-- Last Update -->
                        <div class="pt-4 border-t border-white/20">
                            <div class="flex items-center text-sm text-white/70">
                                <i data-feather="clock" class="w-4 h-4 mr-2"></i>
                                <span>Terakhir diperbarui: <span id="lastUpdate" class="text-white/90 font-medium">-</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>



    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>

    <script>
        // Initialize Feather Icons
        feather.replace();

        // Map and marker variables
        let map;
        let currentMarker = null;
        let savedMarkers = [];

        // Initialize map
        function initMap() {
            // Create map centered on UNNES (koordinat yang lebih tepat untuk area kampus UNNES)
            map = L.map('map').setView([-7.050570, 110.414650], 16);

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Load existing locations
            loadExistingLocations();
        }

        // Handle map click (disabled for view-only mode)
        function onMapClick(e) {
            // Map click disabled in view-only mode
        }

        // Load existing locations from backend
        async function loadExistingLocations() {
            try {
                const response = await fetch('/api/halte');
                const result = await response.json();

                // Check for both Laravel format (success) and Python backend format (meta.status)
                const isSuccess = result.success || (result.meta && result.meta.status === 'success');
                const data = result.data || [];

                if (isSuccess && data) {
                    savedMarkers.forEach(marker => map.removeLayer(marker));
                    savedMarkers = [];

                    data.forEach(location => {
                        if (location.latitude && location.longitude) {
                            // Create custom red camera icon
                            const cameraIcon = L.divIcon({
                                html: '<div style="background: red; width: 16px; height: 16px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>',
                                iconSize: [20, 20],
                                className: 'custom-div-icon'
                            });

                            const marker = L.marker([location.latitude, location.longitude], { icon: cameraIcon })
                                .addTo(map)
                                .bindPopup(`
                                    <div class="p-3">
                                        <h3 class="font-semibold text-gray-900 mb-2">${location.nama_halte}</h3>
                                        <p class="text-sm text-gray-600 mb-3">
                                            Koordinat: ${location.latitude}, ${location.longitude}
                                        </p>
                                        ${location.cctv ? `
                                            <a href="${location.cctv}" target="_blank" 
                                               class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
                                                <span style="margin-right: 8px;">🔗</span>
                                                Lihat CCTV
                                            </a>
                                        ` : '<p class="text-sm text-gray-500">URL CCTV tidak tersedia</p>'}
                                    </div>
                                `);

                            savedMarkers.push(marker);
                        }
                    });
                    
                    // Update statistics
                    updateStatistics(data);
                }
            } catch (error) {
                console.error('Error loading locations:', error);
                console.log('Error loading locations:', error.message);
            }
        }

        // Update statistics display
        function updateStatistics(locations) {
            const totalLocations = locations.length;
            const activeCCTV = locations.filter(location => location.cctv && location.cctv.trim() !== '').length;
            const inactiveCCTV = totalLocations - activeCCTV;
            
            // Update DOM elements
            document.getElementById('totalLocations').textContent = totalLocations;
            document.getElementById('activeCCTV').textContent = activeCCTV;
            document.getElementById('inactiveCCTV').textContent = inactiveCCTV;
            
            // Update last update time
            const now = new Date();
            const timeString = now.toLocaleString('id-ID', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            });
            document.getElementById('lastUpdate').textContent = timeString;
        }

        // Add UNNES area circle
        function addUnnesArea() {
            // Add circle to show UNNES area
            const unnesCircle = L.circle([-7.050570, 110.414650], {
                color: 'blue',
                fillColor: '#3b82f6',
                fillOpacity: 0.1,
                radius: 1000 // 1km radius
            }).addTo(map);

            unnesCircle.bindPopup('<div class="p-2"><strong>Area UNNES</strong><br>Radius 1km dari kampus</div>');
        }

        // Initialize map when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            addUnnesArea();
        });
    </script>
</body>
</html>
