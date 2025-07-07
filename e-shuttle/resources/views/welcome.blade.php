<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'E-Shuttle') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 50%, #10b981 100%);
        }
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .pulse-animation {
            animation: pulse 2s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
    </style>
</head>
<body class="antialiased gradient-bg min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Floating Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-40 h-40 bg-white/10 rounded-full floating-animation" style="animation-delay: 0s;"></div>
        <div class="absolute top-3/4 right-1/4 w-32 h-32 bg-white/10 rounded-full floating-animation" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-3/4 w-24 h-24 bg-white/10 rounded-full floating-animation" style="animation-delay: 4s;"></div>
        <div class="absolute bottom-1/4 left-1/2 w-36 h-36 bg-white/10 rounded-full floating-animation" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/6 right-1/3 w-20 h-20 bg-white/10 rounded-full floating-animation" style="animation-delay: 3s;"></div>
    </div>

    <div class="max-w-4xl w-full mx-auto text-center relative z-10">
        <div class="glass-effect rounded-3xl shadow-2xl p-8 md:p-12 space-y-8 border border-white/20">
            <!-- Logo UNNES -->
            <div class="flex justify-center mb-6">
                <div class="w-20 h-20 pulse-animation">
                    <svg viewBox="0 0 100 100" class="w-full h-full">
                        <defs>
                            <linearGradient id="logoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
                                <stop offset="50%" style="stop-color:#1d4ed8;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#10b981;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <circle cx="50" cy="50" r="45" fill="url(#logoGradient)" stroke="white" stroke-width="2"/>
                        <text x="50" y="58" text-anchor="middle" fill="white" font-size="20" font-weight="bold">UNNES</text>
                    </svg>
                </div>
            </div>

            <h1 class="text-4xl md:text-5xl font-bold text-white tracking-tight">
                Welcome to <span class="bg-gradient-to-r from-blue-300 to-green-300 bg-clip-text text-transparent">E-Shuttle</span>
            </h1>
            
            <p class="text-lg md:text-xl text-white/80 max-w-2xl mx-auto">
                Your modern transportation management solution. Choose your login type to continue.
            </p>

            <div class="grid md:grid-cols-2 gap-6 mt-8 max-w-2xl mx-auto">
                <a href="{{ route('admin.login') }}" 
                   class="group relative overflow-hidden glass-effect rounded-2xl px-6 py-6 text-white shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl border border-white/20">
                    <div class="relative z-10 text-center">
                        <div class="flex justify-center mb-3">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i data-feather="shield" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Admin Login</h3>
                        <p class="text-white/70 text-sm">Access administrative controls</p>
                    </div>
                    <div class="absolute inset-0 z-0 bg-gradient-to-r from-blue-500/20 to-blue-600/20 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl"></div>
                </a>

                <a href="{{ route('user.login') }}"
                   class="group relative overflow-hidden glass-effect rounded-2xl px-6 py-6 text-white shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl border border-white/20">
                    <div class="relative z-10 text-center">
                        <div class="flex justify-center mb-3">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-green-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i data-feather="user" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">User Login</h3>
                        <p class="text-white/70 text-sm">Access your account</p>
                    </div>
                    <div class="absolute inset-0 z-0 bg-gradient-to-r from-green-500/20 to-green-600/20 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl"></div>
                </a>
            </div>
        </div>

        <footer class="mt-8 text-white/60">
            <p class="flex items-center justify-center space-x-2">
                <i data-feather="heart" class="w-4 h-4 text-red-400"></i>
                <span>&copy; {{ date('Y') }} E-Shuttle. All rights reserved.</span>
            </p>
        </footer>
    </div>

    <div class="fixed bottom-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 via-purple-500 to-green-400 opacity-60"></div>

    <script>
        // Initialize Feather Icons
        feather.replace();
    </script>
</body>
</html>
