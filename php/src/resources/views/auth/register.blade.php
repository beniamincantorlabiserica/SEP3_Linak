<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - DeskMaster Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.19/dist/full.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .slide-in {
            animation: slideIn 0.5s ease-out;
        }
        @keyframes slideIn {
            0% { transform: translateY(-20px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        .hero-pattern {
            background-color: #ffffff;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        .main-content {
            flex: 1;
        }
    </style>
</head>
<body class="hero-pattern">
    <!-- Navbar -->
    <div class="navbar bg-base-100 shadow-lg">
        <div class="flex-1">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="DeskMaster Pro Logo" class="h-10 w-auto">
                <span class="btn btn-ghost normal-case text-xl">DeskMaster Pro</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content flex items-center justify-center px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 w-full max-w-7xl h-full lg:h-auto">
            <!-- Register Form -->
            <div class="card bg-base-100 shadow-2xl slide-in">
                <div class="card-body">
                    <h2 class="card-title text-2xl mb-6">Create Your Account</h2>
                    
                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <!-- Personal Information Section -->
                        <div class="divider">Personal Information</div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- First Name -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">First Name</span>
                                </label>
                                <input type="text" 
                                       name="first_name"
                                       value="{{ old('first_name') }}"
                                       class="input input-bordered"
                                       required>
                                <x-input-error :messages="$errors->get('first_name')" class="mt-2 text-error text-sm" />
                            </div>

                            <!-- Last Name -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Last Name</span>
                                </label>
                                <input type="text" 
                                       name="last_name"
                                       value="{{ old('last_name') }}"
                                       class="input input-bordered"
                                       required>
                                <x-input-error :messages="$errors->get('last_name')" class="mt-2 text-error text-sm" />
                            </div>
                        </div>

                        <!-- Physical Information Section -->
                        <div class="divider">Physical Information</div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Age -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Age</span>
                                </label>
                                <input type="number" 
                                       name="age"
                                       value="{{ old('age') }}"
                                       class="input input-bordered"
                                       min="0"
                                       max="120">
                                <x-input-error :messages="$errors->get('age')" class="mt-2 text-error text-sm" />
                            </div>

                            <!-- Height -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Height (cm)</span>
                                </label>
                                <input type="number" 
                                       name="height"
                                       value="{{ old('height') }}"
                                       class="input input-bordered"
                                       min="0"
                                       max="300">
                                <x-input-error :messages="$errors->get('height')" class="mt-2 text-error text-sm" />
                            </div>

                            <!-- Weight -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Weight (kg)</span>
                                </label>
                                <input type="number" 
                                       name="weight"
                                       value="{{ old('weight') }}"
                                       class="input input-bordered"
                                       min="0"
                                       max="500">
                                <x-input-error :messages="$errors->get('weight')" class="mt-2 text-error text-sm" />
                            </div>
                        </div>

                        <!-- Account Information Section -->
                        <div class="divider">Account Information</div>

                        <!-- Email -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Email</span>
                            </label>
                            <input type="email" 
                                   name="email"
                                   value="{{ old('email') }}"
                                   class="input input-bordered"
                                   required>
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-error text-sm" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Password -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Password</span>
                                </label>
                                <input type="password" 
                                       name="password"
                                       class="input input-bordered"
                                       required>
                                <x-input-error :messages="$errors->get('password')" class="mt-2 text-error text-sm" />
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Confirm Password</span>
                                </label>
                                <input type="password" 
                                       name="password_confirmation"
                                       class="input input-bordered"
                                       required>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-error text-sm" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <a href="{{ route('login') }}" class="link link-primary">
                                Already have an account?
                            </a>
                            <button class="btn btn-primary">Register</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Decorative Side -->
            <div class="hidden lg:flex flex-col items-center justify-center p-12">
                <div class="float-animation">
                    <!-- Enhanced Desk Control Illustration -->
                    <svg class="w-full max-w-lg" viewBox="0 0 200 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="20" y="60" width="160" height="10" fill="#4A5568"/>
                        <rect x="30" y="70" width="140" height="5" fill="#2D3748"/>
                        <rect x="35" y="75" width="5" height="60" fill="#4A5568"/>
                        <rect x="160" y="75" width="5" height="60" fill="#4A5568"/>
                        <!-- Enhanced Control Panel -->
                        <rect x="70" y="30" width="60" height="20" rx="3" fill="#4A5568"/>
                        <circle cx="85" cy="40" r="3" fill="#fff"/>
                        <circle cx="100" cy="40" r="3" fill="#fff"/>
                        <circle cx="115" cy="40" r="3" fill="#fff"/>
                        <!-- Display Screen -->
                        <rect x="75" y="33" width="20" height="6" rx="1" fill="#1A202C"/>
                    </svg>
                </div>
                <div class="mt-12 text-center">
                    <h3 class="text-3xl font-bold mb-4">Join DeskMaster Pro</h3>
                    <p class="text-gray-600 text-lg">Create your account to start optimizing your workspace ergonomics today.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer footer-center p-4 bg-base-200 text-base-content">
        <div>
            <p>Copyright © 2024 - All rights reserved by DeskMaster Pro</p>
        </div>
    </footer>
</body>
</html>