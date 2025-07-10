<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($halte) ? 'Edit Halte' : 'Tambah Halte' }} - E-Shuttle Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="min-h-screen gradient-bg">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="glass-effect shadow-lg border-b border-white/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div class="flex items-center space-x-6">
                        <!-- Logo UNNES -->
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                                <img src="{{ asset('images/logo-unnes.png') }}" alt="Logo UNNES" class="w-full h-full object-contain">
                            </div>
                            <div>
                                <h1 class="text-xl font-bold text-white">UNNES</h1>
                                <p class="text-xs text-white/80">E-Shuttle Admin</p>
                            </div>
                        </div>
                        
                        <div class="h-8 w-px bg-white/30"></div>
                        
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center text-white/80 hover:text-white transition-all duration-300 group">
                                <i data-feather="arrow-left" class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Kembali ke Dashboard</span>
                            </a>
                            <div class="h-6 w-px bg-white/30"></div>
                            <h2 class="text-lg font-semibold text-white">
                                {{ isset($halte) ? 'Edit Halte' : 'Tambah Halte Baru' }}
                            </h2>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2 border border-white/20">
                            <div class="w-8 h-8 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center">
                                <i data-feather="user" class="w-4 h-4 text-white"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-white/70">Administrator</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 py-8">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Form Card -->
                <div class="glass-effect shadow-2xl rounded-2xl border border-white/20 overflow-hidden">
                    <div class="px-8 py-6 border-b border-white/20 bg-gradient-to-r from-white/10 to-white/5">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                                <i data-feather="{{ isset($halte) ? 'edit-3' : 'plus-circle' }}" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">
                                    {{ isset($halte) ? 'Edit Data Halte' : 'Tambah Data Halte Baru' }}
                                </h2>
                                <p class="text-sm text-white/80 mt-1">
                                    {{ isset($halte) ? 'Perbarui informasi halte di bawah ini.' : 'Lengkapi informasi halte di bawah ini.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <form id="halteForm" class="p-8">
                        @csrf
                        @if(isset($halte))
                            <input type="hidden" id="halte_id" value="{{ $halte['id_halte'] }}">
                        @endif

                        <!-- Error Message -->
                        <div id="errorMessage" class="hidden bg-red-500/20 backdrop-blur-sm border border-red-400/30 text-red-100 px-6 py-4 rounded-xl mb-6 flex items-center space-x-3">
                            <i data-feather="alert-circle" class="w-5 h-5 text-red-400"></i>
                            <span id="errorText" class="font-medium"></span>
                        </div>

                        <!-- Success Message -->
                        <div id="successMessage" class="hidden bg-green-500/20 backdrop-blur-sm border border-green-400/30 text-green-100 px-6 py-4 rounded-xl mb-6 flex items-center space-x-3">
                            <i data-feather="check-circle" class="w-5 h-5 text-green-400"></i>
                            <span id="successText" class="font-medium"></span>
                        </div>

                        @if(isset($halte))
                            <input type="hidden" id="halte_id" value="{{ $halte->id }}">
                        @endif
                        
                        <div class="grid grid-cols-1 gap-8">
                            <!-- Nama Halte -->
                            <div class="space-y-3">
                                <label for="nama_halte" class="flex items-center text-sm font-semibold text-white mb-3">
                                    <i data-feather="map-pin" class="w-4 h-4 mr-2 text-blue-400"></i>
                                    Nama Halte <span class="text-red-400 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="nama_halte" name="nama_halte" required
                                        class="w-full px-4 py-4 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-white placeholder-white/60 transition-all duration-300 hover:bg-white/15"
                                        placeholder="Masukkan nama halte"
                                        value="{{ isset($halte) ? $halte->nama_halte : '' }}">
                                    <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-blue-500/20 to-purple-500/20 opacity-0 transition-opacity duration-300 pointer-events-none hover:opacity-100"></div>
                                </div>
                            </div>

                            <!-- URL CCTV -->
                            <div class="space-y-3">
                                <label for="cctv_url" class="flex items-center text-sm font-semibold text-white mb-3">
                                    <i data-feather="video" class="w-4 h-4 mr-2 text-green-400"></i>
                                    URL CCTV <span class="text-red-400 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <input type="url" id="cctv_url" name="cctv_url" required
                                        class="w-full px-4 py-4 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent text-white placeholder-white/60 transition-all duration-300 hover:bg-white/15"
                                        placeholder="https://example.com/stream"
                                        value="{{ isset($halte) ? $halte->cctv : '' }}">
                                    <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-green-500/20 to-blue-500/20 opacity-0 transition-opacity duration-300 pointer-events-none hover:opacity-100"></div>
                                </div>
                                <p class="text-xs text-white/70 flex items-center mt-2">
                                    <i data-feather="info" class="w-3 h-3 mr-1"></i>
                                    Masukkan URL stream CCTV yang valid
                                </p>
                            </div>

                            <!-- Koordinat -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-3">
                                    <label for="latitude" class="flex items-center text-sm font-semibold text-white mb-3">
                                        <i data-feather="navigation" class="w-4 h-4 mr-2 text-yellow-400"></i>
                                        Latitude <span class="text-red-400 ml-1">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" step="any" id="latitude" name="latitude" required readonly
                                            class="w-full px-4 py-4 bg-white/5 backdrop-blur-sm border border-white/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent text-white placeholder-white/60 transition-all duration-300"
                                            placeholder="-6.200000"
                                            value="{{ isset($halte) ? $halte->latitude : '' }}">
                                        <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-yellow-500/10 to-orange-500/10 opacity-100 pointer-events-none"></div>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <label for="longitude" class="flex items-center text-sm font-semibold text-white mb-3">
                                        <i data-feather="navigation" class="w-4 h-4 mr-2 text-yellow-400"></i>
                                        Longitude <span class="text-red-400 ml-1">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" step="any" id="longitude" name="longitude" required readonly
                                            class="w-full px-4 py-4 bg-white/5 backdrop-blur-sm border border-white/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent text-white placeholder-white/60 transition-all duration-300"
                                            placeholder="106.816666"
                                            value="{{ isset($halte) ? $halte->longitude : '' }}">
                                        <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-yellow-500/10 to-orange-500/10 opacity-100 pointer-events-none"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Map Container -->
                            <div class="space-y-4">
                                <label class="flex items-center text-sm font-semibold text-white mb-3">
                                    <i data-feather="map" class="w-4 h-4 mr-2 text-purple-400"></i>
                                    Pilih Lokasi Halte <span class="text-red-400 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <div id="map" class="w-full h-96 rounded-2xl border border-white/20 shadow-2xl overflow-hidden"></div>
                                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-purple-500/10 to-blue-500/10 opacity-50 pointer-events-none"></div>
                                </div>
                                <p class="text-xs text-white/70 flex items-center mt-3">
                                    <i data-feather="mouse-pointer" class="w-3 h-3 mr-1"></i>
                                    Klik pada peta untuk memilih lokasi halte. Koordinat akan terisi otomatis.
                                </p>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row sm:justify-end sm:space-x-4 mt-10 space-y-4 sm:space-y-0">
                            <a href="{{ route('admin.dashboard') }}" 
                                class="group w-full sm:w-auto px-8 py-4 bg-white/10 backdrop-blur-sm text-white rounded-xl hover:bg-white/20 transition-all duration-300 text-center font-semibold border border-white/20 hover:border-white/40 hover:scale-105 flex items-center justify-center space-x-2">
                                <i data-feather="x" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                                <span>Batal</span>
                            </a>
                            <button type="submit" id="submitBtn"
                                class="group w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-300 flex items-center justify-center space-x-2 font-semibold shadow-lg hover:shadow-xl hover:scale-105 border border-blue-400/30">
                                <i data-feather="{{ isset($halte) ? 'edit-3' : 'save' }}" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                                <span id="submitText">{{ isset($halte) ? 'Perbarui Halte' : 'Simpan Halte' }}</span>
                                <div id="submitSpinner" class="hidden ml-2">
                                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="glass-effect rounded-2xl p-8 flex items-center space-x-4 border border-white/20 shadow-2xl">
            <div class="relative">
                <div class="animate-spin rounded-full h-8 w-8 border-4 border-white/20 border-t-blue-400"></div>
                <div class="absolute inset-0 rounded-full bg-gradient-to-r from-blue-400/20 to-purple-400/20 animate-pulse"></div>
            </div>
            <div>
                <span class="text-white font-semibold text-lg">Menyimpan data...</span>
                <p class="text-white/70 text-sm mt-1">Mohon tunggu sebentar</p>
            </div>
        </div>
    </div>

    <script>
        // Initialize Feather Icons
        feather.replace();

        // Global variables
        let map;
        let marker;
        const isEdit = {{ isset($halte) ? 'true' : 'false' }};
        const halteId = isEdit ? {{ isset($halte) ? $halte->id : 'null' }} : null;

        // Initialize map
        function initMap() {
            // Default coordinates (UNNES Sekaran - koordinat yang disesuaikan)
            const defaultLat = isEdit ? parseFloat(document.getElementById('latitude').value) : -7.050613;
            const defaultLng = isEdit ? parseFloat(document.getElementById('longitude').value) : 110.398812;
            
            map = L.map('map').setView([defaultLat, defaultLng], 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            // Add existing marker if editing
            if (isEdit && defaultLat && defaultLng) {
                marker = L.marker([defaultLat, defaultLng]).addTo(map);
            }
            
            // Add UNNES area circles
            addUnnesArea();
            
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

        // Show/hide messages
        function showMessage(type, message) {
            const errorDiv = document.getElementById('errorMessage');
            const successDiv = document.getElementById('successMessage');
            
            // Hide both messages first
            errorDiv.classList.add('hidden');
            successDiv.classList.add('hidden');
            
            if (type === 'error') {
                document.getElementById('errorText').textContent = message;
                errorDiv.classList.remove('hidden');
            } else if (type === 'success') {
                document.getElementById('successText').textContent = message;
                successDiv.classList.remove('hidden');
            }
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                errorDiv.classList.add('hidden');
                successDiv.classList.add('hidden');
            }, 5000);
        }

        // Show/hide loading
        function showLoading(show) {
            const overlay = document.getElementById('loadingOverlay');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitSpinner = document.getElementById('submitSpinner');
            
            if (show) {
                overlay.classList.remove('hidden');
                submitBtn.disabled = true;
                submitText.textContent = 'Menyimpan...';
                submitSpinner.classList.remove('hidden');
            } else {
                overlay.classList.add('hidden');
                submitBtn.disabled = false;
                submitText.textContent = isEdit ? 'Perbarui Halte' : 'Simpan Halte';
                submitSpinner.classList.add('hidden');
            }
        }

        // Handle form submission
        document.getElementById('halteForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const namaHalte = document.getElementById('nama_halte').value.trim();
            const cctvUrl = document.getElementById('cctv_url').value.trim();
            const latitude = document.getElementById('latitude').value;
            const longitude = document.getElementById('longitude').value;
            
            // Validation
            if (!namaHalte) {
                showMessage('error', 'Nama halte harus diisi');
                return;
            }
            
            if (!cctvUrl) {
                showMessage('error', 'URL CCTV harus diisi');
                return;
            }
            
            if (!latitude || !longitude) {
                showMessage('error', 'Silakan pilih lokasi halte pada peta');
                return;
            }
            
            showLoading(true);
            
            try {
                const data = {
                    nama_halte: namaHalte,
                    cctv: cctvUrl,
                    latitude: parseFloat(latitude),
                    longitude: parseFloat(longitude)
                };
                
                const url = isEdit ? `/api/halte/${halteId}` : '/api/halte';
                const method = isEdit ? 'PUT' : 'POST';
                
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showMessage('success', result.message || (isEdit ? 'Halte berhasil diperbarui' : 'Halte berhasil ditambahkan'));
                    
                    // Redirect after 2 seconds
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.dashboard") }}';
                    }, 2000);
                } else {
                    showMessage('error', result.message || 'Terjadi kesalahan saat menyimpan data');
                }
            } catch (error) {
                console.error('Error:', error);
                showMessage('error', 'Terjadi kesalahan jaringan. Pastikan server backend berjalan.');
            } finally {
                showLoading(false);
            }
        });

        // Add UNNES area circles
        function addUnnesArea() {
            // Koordinat pusat kampus UNNES yang disesuaikan
            const unnesCenter = [-7.050613, 110.398812];
            
            // Area kampus utama (radius 800m)
            const campusArea = L.circle(unnesCenter, {
                color: '#3b82f6',
                fillColor: '#3b82f6',
                fillOpacity: 0.1,
                radius: 800,
                weight: 2
            }).addTo(map);
            
            campusArea.bindPopup(`
                <div class="text-center">
                    <h3 class="font-bold text-blue-600 mb-2">Area Kampus UNNES</h3>
                    <p class="text-sm text-gray-600">Radius: 800 meter</p>
                    <p class="text-xs text-gray-500 mt-1">Area utama kampus</p>
                </div>
            `);
            
            // Area layanan E-Shuttle yang diperluas (radius 1.5km)
            const serviceArea = L.circle(unnesCenter, {
                color: '#10b981',
                fillColor: '#10b981',
                fillOpacity: 0.05,
                radius: 1500,
                weight: 2,
                dashArray: '10, 10'
            }).addTo(map);
            
            serviceArea.bindPopup(`
                <div class="text-center">
                    <h3 class="font-bold text-green-600 mb-2">Area Layanan E-Shuttle</h3>
                    <p class="text-sm text-gray-600">Radius: 1.5 kilometer</p>
                    <p class="text-xs text-gray-500 mt-1">Jangkauan layanan shuttle</p>
                </div>
            `);
        }

        // Initialize map when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
        });
    </script>
</body>
</html>