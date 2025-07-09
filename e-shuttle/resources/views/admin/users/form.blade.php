<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($user) ? 'Edit User' : 'Tambah User' }} - E-Shuttle Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
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
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center space-x-4">
                        <!-- Logo UNNES -->
                        <div class="w-10 h-10 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center shadow-lg">
                            <img src="{{ asset('images/logo-unnes.png') }}" alt="Logo UNNES" class="w-full h-full object-contain">
                        </div>
                        <a href="{{ route('admin.dashboard') }}" class="text-white/80 hover:text-white hover:scale-110 transition-all duration-300 mr-4">
                            <i data-feather="arrow-left" class="w-5 h-5"></i>
                        </a>
                        <h1 class="text-xl font-semibold text-white">
                            {{ isset($user) ? 'Edit User' : 'Tambah User Baru' }}
                        </h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-white/80">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-red-300 hover:text-red-100 hover:scale-110 transition-all duration-300">
                                <i data-feather="log-out" class="w-5 h-5"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 py-8">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Form Card -->
                <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden border border-white/20">
                    <div class="px-6 py-6 border-b border-white/20">
                        <div class="flex items-center space-x-3 mb-2">
                            <i data-feather="user-plus" class="w-6 h-6 text-white"></i>
                            <h2 class="text-lg font-medium text-white">
                                {{ isset($user) ? 'Edit Informasi User' : 'Informasi User Baru' }}
                            </h2>
                        </div>
                        <p class="text-sm text-white/70">
                            {{ isset($user) ? 'Perbarui informasi user di bawah ini.' : 'Masukkan informasi user baru di bawah ini.' }}
                        </p>
                    </div>

                    <form id="userForm" class="px-6 py-6 space-y-6">
                        @csrf
                        @if(isset($user))
                            @method('PUT')
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                        @endif

                        <!-- Error Messages -->
                        <div id="errorMessages" class="hidden glass-effect border border-red-300/50 text-red-100 px-4 py-3 rounded-xl backdrop-blur-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i data-feather="alert-circle" class="w-5 h-5 text-red-300"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium">Terdapat beberapa kesalahan:</h3>
                                    <ul id="errorList" class="mt-2 text-sm list-disc list-inside"></ul>
                                </div>
                            </div>
                        </div>

                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-white mb-3">
                                <div class="flex items-center space-x-2">
                                    <div class="w-5 h-5 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                        <i data-feather="user" class="w-3 h-3 text-white"></i>
                                    </div>
                                    <span>Nama Lengkap <span class="text-red-300">*</span></span>
                                </div>
                            </label>
                            <div class="relative">
                                <input type="text" id="name" name="name" 
                                       value="{{ isset($user) ? $user->name : old('name') }}"
                                       class="w-full px-4 py-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl shadow-lg focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/40 text-white placeholder-white/60 transition-all duration-300"
                                       placeholder="Masukkan nama lengkap"
                                       required>
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-xl pointer-events-none opacity-0 transition-opacity duration-300 hover:opacity-100"></div>
                            </div>
                            <div id="nameError" class="text-red-300 text-sm mt-2 hidden"></div>
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-white mb-3">
                                <div class="flex items-center space-x-2">
                                    <div class="w-5 h-5 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                        <i data-feather="mail" class="w-3 h-3 text-white"></i>
                                    </div>
                                    <span>Email <span class="text-red-300">*</span></span>
                                </div>
                            </label>
                            <div class="relative">
                                <input type="email" id="email" name="email" 
                                       value="{{ isset($user) ? $user->email : old('email') }}"
                                       class="w-full px-4 py-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl shadow-lg focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/40 text-white placeholder-white/60 transition-all duration-300"
                                       placeholder="Masukkan alamat email"
                                       required>
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-xl pointer-events-none opacity-0 transition-opacity duration-300 hover:opacity-100"></div>
                            </div>
                            <div id="emailError" class="text-red-300 text-sm mt-2 hidden"></div>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-white mb-3">
                                <div class="flex items-center space-x-2">
                                    <div class="w-5 h-5 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                        <i data-feather="lock" class="w-3 h-3 text-white"></i>
                                    </div>
                                    <span>Password 
                                        @if(isset($user))
                                            <span class="text-white/60 text-xs">(Kosongkan jika tidak ingin mengubah)</span>
                                        @else
                                            <span class="text-red-300">*</span>
                                        @endif
                                    </span>
                                </div>
                            </label>
                            <div class="relative">
                                <input type="password" id="password" name="password" 
                                       {{ !isset($user) ? 'required' : '' }}
                                       class="w-full px-4 py-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl shadow-lg focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/40 text-white placeholder-white/60 transition-all duration-300"
                                       placeholder="{{ isset($user) ? 'Masukkan password baru (opsional)' : 'Masukkan password' }}">
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-xl pointer-events-none opacity-0 transition-opacity duration-300 hover:opacity-100"></div>
                            </div>
                            <div id="passwordError" class="text-red-300 text-sm mt-2 hidden"></div>
                        </div>

                        <!-- Konfirmasi Password Field -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-white mb-3">
                                <div class="flex items-center space-x-2">
                                    <div class="w-5 h-5 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                        <i data-feather="lock" class="w-3 h-3 text-white"></i>
                                    </div>
                                    <span>Konfirmasi Password
                                        @if(!isset($user))
                                            <span class="text-red-300">*</span>
                                        @endif
                                    </span>
                                </div>
                            </label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation" 
                                       class="w-full px-4 py-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl shadow-lg focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/40 text-white placeholder-white/60 transition-all duration-300"
                                       placeholder="{{ isset($user) ? 'Konfirmasi password baru (jika diisi)' : 'Konfirmasi password' }}">
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-xl pointer-events-none opacity-0 transition-opacity duration-300 hover:opacity-100"></div>
                            </div>
                            <div id="passwordConfirmationError" class="text-red-300 text-sm mt-2 hidden"></div>
                        </div>

                        <!-- Role Field -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-white mb-3">
                                <div class="flex items-center space-x-2">
                                    <div class="w-5 h-5 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                        <i data-feather="shield" class="w-3 h-3 text-white"></i>
                                    </div>
                                    <span>Role <span class="text-red-300">*</span></span>
                                </div>
                            </label>
                            <div class="relative">
                                <select id="role" name="role" required
                                        class="w-full px-4 py-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl shadow-lg focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white/40 text-white transition-all duration-300">
                                    <option value="" class="bg-gray-800 text-white">Pilih Role</option>
                                    <option value="user" class="bg-gray-800 text-white" {{ (isset($user) && $user->role === 'user') || old('role') === 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" class="bg-gray-800 text-white" {{ (isset($user) && $user->role === 'admin') || old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-xl pointer-events-none opacity-0 transition-opacity duration-300 hover:opacity-100"></div>
                            </div>
                            <div id="roleError" class="text-red-300 text-sm mt-2 hidden"></div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-white/20">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="px-6 py-3 glass-effect border border-white/30 rounded-xl shadow-lg text-sm font-medium text-white hover:bg-white/20 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-white/30 transition-all duration-300 flex items-center space-x-2">
                                <i data-feather="x" class="w-4 h-4"></i>
                                <span>Batal</span>
                            </a>
                            <button type="submit" id="submitBtn"
                                    class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl shadow-lg text-sm font-medium text-white hover:from-blue-600 hover:to-purple-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-white/30 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 transition-all duration-300 flex items-center space-x-2">
                                <span id="submitText" class="flex items-center space-x-2">
                                    <i data-feather="save" class="w-4 h-4"></i>
                                    <span>{{ isset($user) ? 'Update User' : 'Simpan User' }}</span>
                                </span>
                                <span id="loadingText" class="hidden flex items-center space-x-2">
                                    <i data-feather="loader" class="w-4 h-4 animate-spin"></i>
                                    <span>{{ isset($user) ? 'Mengupdate...' : 'Menyimpan...' }}</span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transition-all duration-300 hidden">
        <div class="flex items-center">
            <div id="toastIcon" class="mr-3"></div>
            <div id="toastMessage" class="text-sm font-medium"></div>
        </div>
    </div>

    <script>
        // Initialize Feather icons
        feather.replace();

        class UserFormManager {
            constructor() {
                this.form = document.getElementById('userForm');
                this.submitBtn = document.getElementById('submitBtn');
                this.isEditing = {{ isset($user) ? 'true' : 'false' }};
                this.userId = {{ isset($user) ? $user->id : 'null' }};
                
                this.initializeEventListeners();
            }

            initializeEventListeners() {
                this.form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.handleSubmit();
                });

                // Real-time validation
                document.getElementById('email').addEventListener('blur', () => this.validateEmail());
                document.getElementById('password').addEventListener('input', () => this.validatePassword());
                document.getElementById('password_confirmation').addEventListener('input', () => this.validatePasswordConfirmation());
            }

            validateEmail() {
                const email = document.getElementById('email').value;
                const emailError = document.getElementById('emailError');
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (email && !emailRegex.test(email)) {
                    emailError.textContent = 'Format email tidak valid';
                    emailError.classList.remove('hidden');
                    return false;
                } else {
                    emailError.classList.add('hidden');
                    return true;
                }
            }

            validatePassword() {
                const password = document.getElementById('password').value;
                const passwordError = document.getElementById('passwordError');

                if (password && password.length < 6) {
                    passwordError.textContent = 'Password minimal 6 karakter';
                    passwordError.classList.remove('hidden');
                    return false;
                } else {
                    passwordError.classList.add('hidden');
                    return true;
                }
            }

            validatePasswordConfirmation() {
                const password = document.getElementById('password').value;
                const passwordConfirmation = document.getElementById('password_confirmation').value;
                const passwordConfirmationError = document.getElementById('passwordConfirmationError');

                if (password && passwordConfirmation && password !== passwordConfirmation) {
                    passwordConfirmationError.textContent = 'Konfirmasi password tidak cocok';
                    passwordConfirmationError.classList.remove('hidden');
                    return false;
                } else {
                    passwordConfirmationError.classList.add('hidden');
                    return true;
                }
            }

            async handleSubmit() {
                // Validate form
                if (!this.validateForm()) {
                    return;
                }

                this.setLoading(true);

                try {
                    const formData = new FormData(this.form);
                    const data = Object.fromEntries(formData.entries());

                    // Remove empty password for editing
                    if (this.isEditing && !data.password) {
                        delete data.password;
                        delete data.password_confirmation;
                    }

                    const url = this.isEditing ? `/api/users/${this.userId}` : '/api/users';
                    const method = this.isEditing ? 'PUT' : 'POST';

                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        this.showToast('success', this.isEditing ? 'User berhasil diupdate!' : 'User berhasil ditambahkan!');
                        
                        // Redirect to dashboard after 2 seconds
                        setTimeout(() => {
                            window.location.href = '{{ route("admin.dashboard") }}';
                        }, 2000);
                    } else {
                        throw new Error(result.message || 'Gagal menyimpan data');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    this.showToast('error', 'Gagal menyimpan data: ' + error.message);
                } finally {
                    this.setLoading(false);
                }
            }

            validateForm() {
                let isValid = true;

                // Clear previous errors
                document.querySelectorAll('.text-red-600').forEach(el => el.classList.add('hidden'));

                // Validate required fields
                const name = document.getElementById('name').value.trim();
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value;
                const role = document.getElementById('role').value;

                if (!name) {
                    this.showFieldError('nameError', 'Nama wajib diisi');
                    isValid = false;
                }

                if (!email) {
                    this.showFieldError('emailError', 'Email wajib diisi');
                    isValid = false;
                } else if (!this.validateEmail()) {
                    isValid = false;
                }

                if (!this.isEditing && !password) {
                    this.showFieldError('passwordError', 'Password wajib diisi');
                    isValid = false;
                } else if (password && !this.validatePassword()) {
                    isValid = false;
                }

                if (password && !this.validatePasswordConfirmation()) {
                    isValid = false;
                }

                if (!role) {
                    this.showFieldError('roleError', 'Role wajib dipilih');
                    isValid = false;
                }

                return isValid;
            }

            showFieldError(elementId, message) {
                const errorElement = document.getElementById(elementId);
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
            }

            setLoading(loading) {
                const submitText = document.getElementById('submitText');
                const loadingText = document.getElementById('loadingText');

                if (loading) {
                    submitText.classList.add('hidden');
                    loadingText.classList.remove('hidden');
                    this.submitBtn.disabled = true;
                } else {
                    submitText.classList.remove('hidden');
                    loadingText.classList.add('hidden');
                    this.submitBtn.disabled = false;
                }

                feather.replace();
            }

            showToast(type, message) {
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

                // Auto hide after 5 seconds
                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 5000);
            }
        }

        // Initialize form manager when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            new UserFormManager();
        });
    </script>
</body>
</html>