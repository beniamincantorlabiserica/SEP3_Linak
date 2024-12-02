<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DeskMaster Pro - Smart Desk Control System</title>
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
        .hero-pattern {
            background-color: #ffffff;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="min-h-screen hero-pattern">
    <!-- Navbar -->
    <div class="navbar bg-base-100 shadow-lg">
        <div class="flex-1">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="DeskMaster Pro Logo" class="h-10 w-auto">
                <span class="btn btn-ghost normal-case text-xl">DeskMaster Pro</span>
            </a>
        </div>
        <div class="flex-none gap-2">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-ghost">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                @endif
            @endauth
        </div>
    </div>
    <!-- Hero Section -->
    <div class="hero min-h-[80vh]">
        <div class="hero-content flex-col lg:flex-row-reverse">
            <div class="lg:w-1/2 float-animation">
                <!-- Simple desk SVG illustration -->
                <svg class="w-full max-w-lg" viewBox="0 0 200 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="20" y="60" width="160" height="10" fill="#4A5568" class="transition-all duration-1000 ease-in-out"/>
                    <rect x="30" y="70" width="140" height="5" fill="#2D3748"/>
                    <rect x="35" y="75" width="5" height="60" fill="#4A5568"/>
                    <rect x="160" y="75" width="5" height="60" fill="#4A5568"/>
                </svg>
            </div>
            <div class="lg:w-1/2">
                <h1 class="text-5xl font-bold">Elevate Your Workspace</h1>
                <p class="py-6 text-xl">Take control of your adjustable desk with DeskMaster Pro. Track positions, set favorites, and analyze your standing habits for a healthier work routine.</p>
                <div class="space-x-4">
                    <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                    <a href="#features" class="btn btn-outline">Learn More</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Smart Features for Smart Desks</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Height Control Card -->
                <div class="card bg-base-100 shadow-xl hover:-translate-y-2 transition-transform duration-300">
                    <div class="card-body">
                        <div class="badge badge-primary mb-4">Control</div>
                        <h3 class="card-title">Precise Height Control</h3>
                        <p>Adjust your desk height with millimeter precision using our intuitive controls.</p>
                    </div>
                </div>
                <!-- Presets Card -->
                <div class="card bg-base-100 shadow-xl hover:-translate-y-2 transition-transform duration-300">
                    <div class="card-body">
                        <div class="badge badge-secondary mb-4">Comfort</div>
                        <h3 class="card-title">Position Presets</h3>
                        <p>Save your favorite desk positions for quick and easy adjustments throughout the day.</p>
                    </div>
                </div>
                <!-- Analytics Card -->
                <div class="card bg-base-100 shadow-xl hover:-translate-y-2 transition-transform duration-300">
                    <div class="card-body">
                        <div class="badge badge-accent mb-4">Insights</div>
                        <h3 class="card-title">Usage Analytics</h3>
                        <p>Track your sitting and standing patterns to optimize your healthy work habits.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer footer-center p-10 bg-base-200 text-base-content rounded">
        <div>
            <p class="font-bold">
                DeskMaster Pro <br/>Smart Desk Control System
            </p>
            <p>Copyright © 2024 - All rights reserved</p>
        </div>
    </footer>
</body>
</html>