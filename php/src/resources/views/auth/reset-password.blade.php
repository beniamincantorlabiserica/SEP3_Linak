<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - DeskMaster Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.19/dist/full.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        .rotate-animation {
            animation: rotate 20s linear infinite;
        }
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
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
            <!-- Reset Password Form -->
            <div class="card bg-base-100 shadow-2xl slide-in">
                <div class="card-body">
                    <div class="text-center mb-8">
                        <!-- Lock Icon -->
                        <div class="w-24 h-24 mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-full h-full text-primary rotate-animation">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold mb-2">Reset Your Password</h2>
                        <p class="text-gray-600">Create a new secure password for your account</p>
                    </div>

                    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                        @csrf
                        
                        <!-- Hidden Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email Address -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text text-base">Email</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   value="{{ old('email', $request->email) }}" 
                                   class="input input-bordered input-lg" 
                                   required 
                                   readonly>
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-error text-sm" />
                        </div>

                        <!-- Password -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text text-base">New Password</span>
                            </label>
                            <input type="password" 
                                   name="password" 
                                   class="input input-bordered input-lg" 
                                   required 
                                   autocomplete="new-password">
                            <label class="label">
                                <span class="label-text-alt">Must be at least 8 characters</span>
                            </label>
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-error text-sm" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text text-base">Confirm New Password</span>
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   class="input input-bordered input-lg" 
                                   required 
                                   autocomplete="new-password">
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-error text-sm" />
                        </div>

                        <div class="form-control mt-8">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Reset Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Decorative Side -->
            <div class="hidden lg:flex flex-col items-center justify-center p-12">
                <div class="max-w-md text-center">
                    <div class="mb-8">
                        <!-- Security Shield Illustration -->
                        <svg class="w-64 h-64 mx-auto text-primary" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L3 7v6a12 12 0 0 0 9 11.6A12 12 0 0 0 21 13V7l-9-5zm0 6a3 3 0 1 1 0 6 3 3 0 0 1 0-6zm0 0a3 3 0 1 1 0 6 3 3 0 0 1 0-6z" 
                                  stroke="currentColor" 
                                  stroke-width="2" 
                                  stroke-linecap="round" 
                                  stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold mb-4">Secure Your Account</h3>
                    <p class="text-gray-600 text-lg">
                        Choose a strong password to protect your desk settings and personal information.
                    </p>
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