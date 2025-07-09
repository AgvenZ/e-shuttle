<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Dashboard - E-Shuttle UNNES</title>
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
    <div class="min-h-screen">
        <!-- Main Content -->
        <div class="w-full">
            <!-- Top Bar -->
            <header class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg shadow-lg border-b border-gray-200/50 dark:border-gray-700/50">
                <div class="flex items-center justify-between h-20 px-6">
                    <div class="flex items-center space-x-3">
                        <!-- Logo UNNES -->
                        <div class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <img src="{{ asset('images/logo-unnes.png') }}" alt="Logo UNNES" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">User Dashboard</h1>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">E-Shuttle Monitoring Portal</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">User</p>
                        </div>
                        <div class="relative">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg ring-2 ring-blue-200 dark:ring-blue-800">
                                <span class="text-white text-sm font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white dark:border-gray-800"></div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-red-500 hover:to-pink-600 hover:text-white rounded-xl group transition-all duration-300 transform hover:scale-105">
                                <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg mr-2 group-hover:bg-white/20 transition-colors duration-300">
                                    <i data-feather="log-out" class="w-4 h-4 text-red-600 dark:text-red-400 group-hover:text-white"></i>
                                </div>
                                <span class="font-medium text-sm">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="p-6">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg rounded-2xl shadow-xl p-6 border border-gray-200/50 dark:border-gray-700/50 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Total Halte</h2>
                                <p class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mt-2">{{ $stats['total_halte'] }}</p>
                                <div class="flex items-center mt-2">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Lokasi halte tersedia</span>
                                </div>
                            </div>
                            <div class="p-4 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl shadow-lg">
                                <i data-feather="map-pin" class="w-8 h-8 text-white"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg rounded-2xl shadow-xl p-6 border border-gray-200/50 dark:border-gray-700/50 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">CCTV Aktif</h2>
                                <p class="text-3xl font-bold bg-gradient-to-r from-green-600 to-teal-600 bg-clip-text text-transparent mt-2">{{ $stats['active_cctv'] }}</p>
                                <div class="flex items-center mt-2">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Kamera monitoring aktif</span>
                                </div>
                            </div>
                            <div class="p-4 bg-gradient-to-br from-green-500 to-teal-600 rounded-2xl shadow-lg">
                                <i data-feather="camera" class="w-8 h-8 text-white"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg rounded-2xl shadow-xl p-6 border border-gray-200/50 dark:border-gray-700/50 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Deteksi Hari Ini</h2>
                                <p class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mt-2">{{ $stats['total_kerumunan_today'] }}</p>
                                <div class="flex items-center mt-2">
                                    <div class="w-2 h-2 bg-purple-500 rounded-full mr-2"></div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Kerumunan terdeteksi</span>
                                </div>
                            </div>
                            <div class="p-4 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl shadow-lg">
                                <i data-feather="activity" class="w-8 h-8 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Recent Detections -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50">
                        <div class="p-6 border-b border-gray-200/50 dark:border-gray-700/50">
                            <h3 class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Deteksi Terbaru</h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">10 deteksi kerumunan terakhir</p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4 max-h-96 overflow-y-auto">
                                @forelse($recent_kerumunan as $kerumunan)
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                                            <i data-feather="users" class="w-5 h-5 text-white"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">{{ $kerumunan->halte->nama_halte ?? 'Unknown' }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $kerumunan->waktu->format('d M Y, H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $kerumunan->jumlah_kerumunan }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">orang</p>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-8">
                                    <i data-feather="inbox" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                                    <p class="text-gray-500 dark:text-gray-400">Belum ada deteksi kerumunan</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- CCTV Status -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50">
                        <div class="p-6 border-b border-gray-200/50 dark:border-gray-700/50">
                            <h3 class="text-xl font-bold bg-gradient-to-r from-green-600 to-teal-600 bg-clip-text text-transparent">Status CCTV</h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">Monitoring kamera real-time</p>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-700 rounded-xl p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Aktif</p>
                                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $cctv_stats['active_cameras'] }}</p>
                                        </div>
                                        <i data-feather="check-circle" class="w-8 h-8 text-green-500"></i>
                                    </div>
                                </div>
                                <div class="bg-gradient-to-br from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 border border-red-200 dark:border-red-700 rounded-xl p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tidak Aktif</p>
                                            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $cctv_stats['inactive_cameras'] }}</p>
                                        </div>
                                        <i data-feather="x-circle" class="w-8 h-8 text-red-500"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 border border-blue-200 dark:border-blue-700 rounded-xl p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Coverage Area</p>
                                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $cctv_stats['coverage_area'] }}</p>
                                    </div>
                                    <i data-feather="shield" class="w-8 h-8 text-blue-500"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map Section -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50">
                    <div class="p-6 border-b border-gray-200/50 dark:border-gray-700/50">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-teal-600 rounded-xl flex items-center justify-center">
                                <i data-feather="map" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold bg-gradient-to-r from-green-600 to-teal-600 bg-clip-text text-transparent">Peta Lokasi Halte</h3>
                                <p class="text-gray-600 dark:text-gray-400 mt-1">Lokasi halte dan status CCTV</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div id="map" class="w-full h-96 rounded-xl border border-gray-200 dark:border-gray-700"></div>
                    </div>
                </div>
            </main>
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

    <script>
        // Initialize Feather Icons
        feather.replace();

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
        
        // Close modal when clicking outside
        document.getElementById('cctvModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCctvModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCctvModal();
            }
        });

        // Initialize map
        const map = L.map('map').setView([-7.0051, 110.4381], 13); // Semarang coordinates

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add halte markers
        @if(isset($halte_data) && $halte_data->count() > 0)
            @foreach($halte_data as $halte)
                @if($halte->latitude && $halte->longitude)
                    const marker{{ $halte->id }} = L.marker([{{ $halte->latitude }}, {{ $halte->longitude }}])
                        .addTo(map)
                        .bindPopup(`
                            <div class="p-3 min-w-64">
                                <h3 class="font-bold text-lg mb-2">{{ $halte->nama_halte }}</h3>
                                <div class="mb-3 space-y-1">
                                    <p class="text-sm text-gray-600">Status CCTV: 
                                        <span class="font-semibold {{ $halte->cctv ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $halte->cctv ? 'Aktif' : 'Tidak Aktif' }}
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
                                            <div>Lat: {{ $halte->latitude }}</div>
                                            <div>Lng: {{ $halte->longitude }}</div>
                                        </div>
                                    </div>
                                </div>
                                @if($halte->cctv)
                                    <div class="mt-3">
                                        <button onclick="openCctvModal('{{ $halte->nama_halte }}', '{{ $halte->cctv }}')" 
                                                class="w-full bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center space-x-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>Lihat CCTV</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        `);
                @endif
            @endforeach
        @endif
    </script>
</body>
</html>