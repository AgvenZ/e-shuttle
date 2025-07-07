<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - {{ config('app.name', 'E-Shuttle') }}</title>
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
<body class="antialiased gradient-bg min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/10 rounded-full floating-animation"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-white/5 rounded-full floating-animation" style="animation-delay: -3s;"></div>
        <div class="absolute top-1/2 left-1/4 w-32 h-32 bg-yellow-400/20 rounded-full floating-animation" style="animation-delay: -1s;"></div>
    </div>

    <div class="max-w-md w-full relative z-10">
        <div class="glass-effect rounded-3xl shadow-2xl p-8 space-y-8 border border-white/20">
            <!-- Logo and Header -->
            <div class="text-center space-y-4">
                <div class="flex justify-center">
                    <div class="w-20 h-20 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-12 h-12 text-white" viewBox="0 0 100 100" fill="currentColor">
                            <path d="M50 10 L60 30 L80 25 L70 45 L90 50 L70 55 L80 75 L60 70 L50 90 L40 70 L20 75 L30 55 L10 50 L30 45 L20 25 L40 30 Z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white">UNNES</h1>
                    <p class="text-white/80 font-medium">E-Shuttle Admin</p>
                </div>
                <div class="h-px bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
                <div>
                    <h2 class="text-xl font-semibold text-white">Admin Login</h2>
                    <p class="mt-2 text-sm text-white/70">Enter your credentials to access the admin dashboard</p>
                </div>
            </div>

            @if ($errors->any())
                <div class="bg-red-500/20 backdrop-blur-sm border border-red-400/30 text-red-100 p-4 rounded-xl text-sm flex items-start space-x-3">
                    <i data-feather="alert-circle" class="w-5 h-5 text-red-400 mt-0.5 flex-shrink-0"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p class="font-medium">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6">
                @csrf
                <div class="space-y-3">
                    <label for="email" class="flex items-center text-sm font-semibold text-white mb-3">
                        <i data-feather="mail" class="w-4 h-4 mr-2 text-blue-400"></i>
                        Email Address
                    </label>
                    <div class="relative">
                        <input type="email" name="email" id="email" required
                               class="w-full px-4 py-4 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-white placeholder-white/60 transition-all duration-300 hover:bg-white/15"
                               placeholder="admin@example.com">
                        <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-blue-500/20 to-purple-500/20 opacity-0 transition-opacity duration-300 pointer-events-none hover:opacity-100"></div>
                    </div>
                </div>

                <div class="space-y-3">
                    <label for="password" class="flex items-center text-sm font-semibold text-white mb-3">
                        <i data-feather="lock" class="w-4 h-4 mr-2 text-green-400"></i>
                        Password
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                               class="w-full px-4 py-4 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent text-white placeholder-white/60 transition-all duration-300 hover:bg-white/15"
                               placeholder="••••••••">
                        <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-green-500/20 to-blue-500/20 opacity-0 transition-opacity duration-300 pointer-events-none hover:opacity-100"></div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" name="remember" id="remember"
                               class="h-4 w-4 text-blue-400 focus:ring-blue-400 border-white/20 rounded bg-white/10 backdrop-blur-sm">
                        <label for="remember" class="text-sm text-white/80 font-medium">Remember me</label>
                    </div>
                </div>

                <button type="submit"
                        class="group w-full flex justify-center items-center space-x-2 py-4 px-6 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl hover:scale-105 border border-blue-400/30">
                        <i data-feather="log-in" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                        <span>Sign in</span>
                </button>
            </form>

            <div class="text-center mt-6">
                <a href="{{ route('welcome') }}" class="group inline-flex items-center space-x-2 text-sm text-white/70 hover:text-white transition-all duration-300">
                    <i data-feather="arrow-left" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                    <span class="font-medium">Back to Welcome Page</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Initialize Feather Icons
        feather.replace();
    </script>
</body>
</html>