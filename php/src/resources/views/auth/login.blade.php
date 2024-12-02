<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - DeskMaster Pro</title>
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
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 w-full max-w-6xl h-full lg:h-auto">
            <!-- Login Form -->
            <div class="card bg-base-100 shadow-2xl slide-in">
                <div class="card-body">
                    <h2 class="card-title text-2xl mb-6">Welcome Back!</h2>
                    
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email Address -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text text-lg">Email</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   class="input input-bordered input-lg w-full" 
                                   required 
                                   autofocus 
                                   autocomplete="username">
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-error text-sm" />
                        </div>

                        <!-- Password -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text text-lg">Password</span>
                            </label>
                            <input type="password" 
                                   name="password" 
                                   class="input input-bordered input-lg w-full" 
                                   required 
                                   autocomplete="current-password">
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-error text-sm" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between mt-8">
                            <label class="label cursor-pointer">
                                <input type="checkbox" 
                                       name="remember" 
                                       class="checkbox checkbox-primary">
                                <span class="label-text text-base ml-2">Remember me</span>
                            </label>
                            
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" 
                                   class="link link-primary text-base">
                                    Forgot your password?
                                </a>
                            @endif
                        </div>

                        <div class="form-control mt-8">
                            <button class="btn btn-primary btn-lg">Log in</button>
                        </div>

                        <!-- Registration Link -->
                        <div class="text-center mt-6">
                            <span class="text-base">Don't have an account?</span>
                            <a href="{{ route('register') }}" class="link link-primary text-base ml-1">Register now</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Decorative Side -->
            <div class="hidden lg:flex flex-col items-center justify-center p-12">
                <div class="float-animation">
                    <!-- Desk Control Illustration -->
                    <svg class="w-full max-w-lg" viewBox="0 0 200 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="20" y="60" width="160" height="10" fill="#4A5568" class="transition-all duration-1000 ease-in-out"/>
                        <rect x="30" y="70" width="140" height="5" fill="#2D3748"/>
                        <rect x="35" y="75" width="5" height="60" fill="#4A5568"/>
                        <rect x="160" y="75" width="5" height="60" fill="#4A5568"/>
                        <!-- Control Panel -->
                        <rect x="80" y="40" width="40" height="15" rx="2" fill="#4A5568"/>
                        <circle cx="90" cy="47.5" r="2" fill="#fff"/>
                        <circle cx="100" cy="47.5" r="2" fill="#fff"/>
                        <circle cx="110" cy="47.5" r="2" fill="#fff"/>
                    </svg>
                </div>
                <div class="mt-12 text-center">
                    <h3 class="text-3xl font-bold mb-4">Control Your Workspace</h3>
                    <p class="text-gray-600 text-lg">Log in to access your personalized desk settings and analytics.</p>
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