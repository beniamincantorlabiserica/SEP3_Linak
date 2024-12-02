<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirm Password - DeskMaster Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.19/dist/full.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        .shield-animation {
            animation: shield 2s ease-in-out infinite;
        }
        @keyframes shield {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
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
            <!-- Confirm Password Form -->
            <div class="card bg-base-100 shadow-2xl slide-in">
                <div class="card-body">
                    <div class="text-center mb-8">
                        <!-- Shield Icon -->
                        <div class="w-24 h-24 mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-full h-full text-primary shield-animation">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold mb-2">Secure Area</h2>
                        <p class="text-gray-600">Please confirm your password to continue</p>
                    </div>

                    <div class="alert alert-warning mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>This is a secure area. Verification required to proceed.</span>
                    </div>

                    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                        @csrf

                        <!-- Password -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text text-base">Password</span>
                            </label>
                            <input type="password" 
                                   name="password" 
                                   class="input input-bordered input-lg" 
                                   required 
                                   autocomplete="current-password">
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-error text-sm" />
                        </div>

                        <div class="form-control mt-8">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Confirm Password
                            </button>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="text-center mt-4">
                                <a href="{{ route('password.request') }}" class="link link-primary text-sm">
                                    Forgot your password?
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Decorative Side -->
            <div class="hidden lg:flex flex-col items-center justify-center p-12">
                <div class="max-w-md text-center">
                    <div class="mb-8">
                        <!-- Security Illustration -->
                        <svg class="w-64 h-64 mx-auto text-primary" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                  d="M12 15v2m0 0v2m0-2h2m-2 0H9m3-8a3 3 0 100 6 3 3 0 000-6zm7-3h-1V6a6 6 0 10-12 0v3H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold mb-4">Enhanced Security</h3>
                    <p class="text-gray-600 text-lg">
                        We're protecting your workspace settings and personal information with an extra layer of security.
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