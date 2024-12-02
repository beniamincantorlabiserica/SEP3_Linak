<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password - DeskMaster Pro</title>
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
        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
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
            <!-- Forgot Password Form -->
            <div class="card bg-base-100 shadow-2xl slide-in">
                <div class="card-body">
                    <div class="text-center mb-8">
                        <!-- Key Icon -->
                        <div class="w-24 h-24 mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-full h-full text-primary pulse">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold mb-2">Forgot Your Password?</h2>
                        <p class="text-gray-600">No worries! We'll help you recover your account.</p>
                    </div>

                    <div class="alert alert-info mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Enter your email address and we'll send you a password reset link.</span>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                        @csrf

                        <!-- Email Address -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text text-base">Email Address</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   class="input input-bordered input-lg" 
                                   required 
                                   autofocus>
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-error text-sm" />
                        </div>

                        <div class="form-control mt-8">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Send Reset Link
                            </button>
                        </div>

                        <div class="text-center mt-6">
                            <a href="{{ route('login') }}" class="link link-primary">
                                Back to Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Decorative Side -->
            <div class="hidden lg:flex flex-col items-center justify-center p-12">
                <div class="max-w-md text-center">
                    <div class="mb-8">
                        <!-- Email Recovery Illustration -->
                        <svg class="w-64 h-64 mx-auto text-primary float-animation" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <!-- <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/> -->
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold mb-4">Password Recovery</h3>
                    <p class="text-gray-600 text-lg">
                        We'll help you get back to managing your workspace settings in no time.
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