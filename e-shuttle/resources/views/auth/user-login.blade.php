<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Login - {{ config('app.name', 'E-Shuttle') }}</title>
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
    <!-- Floating Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-32 h-32 bg-white/10 rounded-full floating-animation" style="animation-delay: 0s;"></div>
        <div class="absolute top-3/4 right-1/4 w-24 h-24 bg-white/10 rounded-full floating-animation" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-3/4 w-20 h-20 bg-white/10 rounded-full floating-animation" style="animation-delay: 4s;"></div>
        <div class="absolute bottom-1/4 left-1/2 w-28 h-28 bg-white/10 rounded-full floating-animation" style="animation-delay: 1s;"></div>
    </div>

    <div class="max-w-md w-full relative z-10">
        <div class="glass-effect rounded-3xl shadow-2xl p-8 space-y-6 border border-white/20">
            <!-- Logo UNNES -->
            <div class="text-center mb-6">
                <div class="mx-auto w-16 h-16 mb-4 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg">
                    <img src="{{ asset('images/logo-unnes.png') }}" alt="Logo UNNES" class="w-full h-full object-contain">
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">User Login</h1>
                <p class="text-white/80 text-sm">Enter your credentials to access your account</p>
            </div>

            @if ($errors->any())
                <div class="backdrop-blur-sm bg-red-500/20 border border-red-400/30 text-red-100 p-4 rounded-xl text-sm flex items-start space-x-3">
                    <i data-feather="alert-circle" class="w-5 h-5 mt-0.5 flex-shrink-0"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('user.login.submit') }}" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label for="email" class="flex items-center text-sm font-medium text-white/90 mb-2">
                        <i data-feather="mail" class="w-4 h-4 mr-2"></i>
                        Email Address
                    </label>
                    <div class="relative">
                        <input type="email" name="email" id="email" required
                               class="w-full px-4 py-3 backdrop-blur-sm bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/40 transition-all duration-300 hover:bg-white/15"
                               placeholder="user@example.com">
                        <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-transparent via-white/5 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="password" class="flex items-center text-sm font-medium text-white/90 mb-2">
                        <i data-feather="lock" class="w-4 h-4 mr-2"></i>
                        Password
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                               class="w-full px-4 py-3 backdrop-blur-sm bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/40 transition-all duration-300 hover:bg-white/15"
                               placeholder="••••••••">
                        <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-transparent via-white/5 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                    </div>
                </div>



                <button type="submit"
                        class="w-full flex items-center justify-center py-3 px-6 backdrop-blur-sm bg-gradient-to-r from-green-400 to-green-600 border border-green-400/30 rounded-xl shadow-lg text-white font-medium transition-all duration-300 hover:from-green-500 hover:to-green-700 hover:scale-105 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-green-400/50 group">
                    <i data-feather="log-in" class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform duration-300"></i>
                    Sign in
                </button>
            </form>

            <div class="text-center mt-6">
                <a href="{{ route('welcome') }}" class="inline-flex items-center text-sm text-white/70 hover:text-white transition-all duration-300 hover:scale-105 group">
                    <i data-feather="arrow-left" class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-300"></i>
                    Back to Welcome Page
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
