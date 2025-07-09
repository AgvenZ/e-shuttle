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
                        <div class="">

                        </div>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 text-white/80 hover:text-white hover:scale-105 transition-all duration-300">
                            <i data-feather="arrow-left" class="w-5 h-5"></i>
                            <span>Kembali ke Dashboard</span>
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Navigation items removed -->
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
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-3">
                                <i data-feather="map-pin" class="w-6 h-6 text-white"></i>
                                <h2 class="text-xl font-semibold text-white">Peta Lokasi UNNES</h2>
                            </div>
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
                                    <span id="coverageArea" class="text-2xl font-bold text-purple-300">0%</span>
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

    <!-- CCTV Modal -->
    <div id="cctvModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 id="cctvModalTitle" class="text-xl font-bold text-gray-900 dark:text-white">CCTV Feed</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Live monitoring</p>
                    </div>
                </div>
                <button onclick="closeCctvModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-colors duration-200">
                    <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div class="bg-gray-100 dark:bg-gray-700 rounded-xl overflow-hidden">
                    <div id="cctvContent" class="w-full h-96 flex items-center justify-center">
                        <div class="text-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto mb-4"></div>
                            <p class="text-gray-500 dark:text-gray-400">Loading CCTV feed...</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-700">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Live Stream</span>
                    </div>
                    <p id="cctvUrl" class="text-xs text-blue-600 dark:text-blue-400 mt-1 font-mono"></p>
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
            // Create map centered on UNNES Sekaran (koordinat yang disesuaikan)
            map = L.map('map').setView([-7.050613, 110.398812], 15);

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
                            const marker = L.marker([location.latitude, location.longitude])
                                .addTo(map)
                                .bindPopup(`
                                    <div class="p-3 min-w-64">
                                        <h3 class="font-bold text-lg mb-2">${location.nama_halte}</h3>
                                        <div class="mb-3 space-y-1">
                                            <p class="text-sm text-gray-600">Status CCTV:
                                                <span class="font-semibold ${location.cctv ? 'text-green-600' : 'text-red-600'}">
                                                    ${location.cctv ? 'Aktif' : 'Tidak Aktif'}
                                                </span>
                                            </p>
                                            <div class="text-xs text-gray-500 bg-gray-50 p-2 rounded border">
                                                <div class="flex items-center space-x-1 mb-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    <span class="font-medium">Koordinat:</span>
                                                </div>
                                                <div class="font-mono text-xs">
                                                    <div>Lat: ${location.latitude}</div>
                                                    <div>Lng: ${location.longitude}</div>
                                                </div>
                                            </div>
                                        </div>
                                        ${location.cctv ? `
                                            <div class="mt-3">
                                                <button onclick="openCctvModal('${location.nama_halte}', '${location.cctv}')"
                                                    class="w-full bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center space-x-2">
                                                     <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                     </svg>
                                                     <span class="text-white">Lihat CCTV</span>
                                                 </button>
                                            </div>
                                        ` : ''}
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
            const coveragePercentage = totalLocations > 0 ? Math.round((activeCCTV / totalLocations) * 100) : 0;

            // Update DOM elements
            document.getElementById('totalLocations').textContent = totalLocations;
            document.getElementById('activeCCTV').textContent = activeCCTV;
            document.getElementById('inactiveCCTV').textContent = inactiveCCTV;
            document.getElementById('coverageArea').textContent = coveragePercentage + '%';

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
            // Koordinat yang disesuaikan untuk kampus UNNES Sekaran
            const unnesCenter = [-7.050613, 110.398812]; // Koordinat pusat kampus UNNES yang disesuaikan

            // Area utama kampus UNNES dengan radius yang lebih sesuai
            const unnesMainArea = L.circle(unnesCenter, {
                color: '#1e40af',
                fillColor: '#3b82f6',
                fillOpacity: 0.15,
                weight: 2,
                radius: 800 // 800m radius untuk area utama kampus
            }).addTo(map);

            unnesMainArea.bindPopup(`
                <div class="p-3 min-w-48">
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <strong class="text-blue-700">Area Kampus UNNES</strong>
                    </div>
                    <div class="text-sm text-gray-600 space-y-1">
                        <div>📍 Sekaran, Gunungpati</div>
                        <div>📏 Radius: 800m</div>
                        <div>🎓 Universitas Negeri Semarang</div>
                    </div>
                </div>
            `);

            // Area perluasan untuk zona shuttle (radius lebih besar)
            const unnesExtendedArea = L.circle(unnesCenter, {
                color: '#60a5fa',
                fillColor: '#93c5fd',
                fillOpacity: 0.08,
                weight: 1,
                dashArray: '5, 5',
                radius: 1500 // 1.5km radius untuk area perluasan shuttle
            }).addTo(map);

            unnesExtendedArea.bindPopup(`
                <div class="p-3 min-w-48">
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="w-3 h-3 bg-blue-300 rounded-full"></div>
                        <strong class="text-blue-600">Area Layanan E-Shuttle</strong>
                    </div>
                    <div class="text-sm text-gray-600 space-y-1">
                        <div>🚌 Jangkauan layanan shuttle</div>
                        <div>📏 Radius: 1.5km</div>
                        <div>🗺️ Meliputi area sekitar kampus</div>
                    </div>
                </div>
            `);
        }

        // CCTV Modal Functions
        function openCctvModal(halteName, cctvUrl) {
            const modal = document.getElementById('cctvModal');
            const title = document.getElementById('cctvModalTitle');
            const content = document.getElementById('cctvContent');
            const urlDisplay = document.getElementById('cctvUrl');

            title.textContent = `CCTV ${halteName}`;
            urlDisplay.textContent = cctvUrl;

            // Show loading state
            content.innerHTML = `
                <div class="text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto mb-4"></div>
                    <p class="text-gray-500 dark:text-gray-400">Loading CCTV feed...</p>
                </div>
            `;

            modal.classList.remove('hidden');

            // Load CCTV feed
            setTimeout(() => {
                if (cctvUrl.includes('youtube.com') || cctvUrl.includes('youtu.be')) {
                    // Handle YouTube URLs
                    let videoId = '';
                    if (cctvUrl.includes('youtube.com/watch?v=')) {
                        videoId = cctvUrl.split('v=')[1].split('&')[0];
                    } else if (cctvUrl.includes('youtu.be/')) {
                        videoId = cctvUrl.split('youtu.be/')[1].split('?')[0];
                    }

                    if (videoId) {
                        content.innerHTML = `
                            <iframe
                                width="100%"
                                height="384"
                                src="https://www.youtube.com/embed/${videoId}?autoplay=1&mute=1"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                                class="rounded-lg">
                            </iframe>
                        `;
                    } else {
                        showCctvError('Invalid YouTube URL format');
                    }
                } else if (cctvUrl.includes('rtsp://') || cctvUrl.includes('rtmp://')) {
                    // Handle RTSP/RTMP streams
                    content.innerHTML = `
                        <div class="text-center py-16">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">RTSP/RTMP Stream</h4>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">This stream requires a compatible media player</p>
                            <a href="${cctvUrl}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Open in External Player
                            </a>
                        </div>
                    `;
                } else {
                    // Handle other video URLs or direct streams
                    content.innerHTML = `
                        <video
                            width="100%"
                            height="384"
                            controls
                            autoplay
                            muted
                            class="rounded-lg bg-black"
                            onloadstart="this.volume=0.5"
                            onerror="showCctvError('Failed to load video stream')">
                            <source src="${cctvUrl}" type="video/mp4">
                            <source src="${cctvUrl}" type="video/webm">
                            <source src="${cctvUrl}" type="video/ogg">
                            Your browser does not support the video tag.
                        </video>
                    `;
                }
            }, 500);
        }

        function closeCctvModal() {
            const modal = document.getElementById('cctvModal');
            const content = document.getElementById('cctvContent');

            modal.classList.add('hidden');

            // Clear content to stop any playing media
            content.innerHTML = `
                <div class="text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto mb-4"></div>
                    <p class="text-gray-500 dark:text-gray-400">Loading CCTV feed...</p>
                </div>
            `;
        }

        function showCctvError(message) {
            const content = document.getElementById('cctvContent');
            content.innerHTML = `
                <div class="text-center py-16">
                    <svg class="w-16 h-16 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h4 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-2">Error Loading Stream</h4>
                    <p class="text-gray-500 dark:text-gray-400">${message}</p>
                </div>
            `;
        }

        // Initialize map when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            addUnnesArea();

            // Close modal when clicking outside
            document.addEventListener('click', function(event) {
                const modal = document.getElementById('cctvModal');
                if (event.target === modal) {
                    closeCctvModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    const modal = document.getElementById('cctvModal');
                    if (!modal.classList.contains('hidden')) {
                        closeCctvModal();
                    }
                }
            });
        });
    </script>
</body>
</html>
