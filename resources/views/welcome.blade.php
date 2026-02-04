<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Campus Parking') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .feature-card {
            transition: all 0.3s ease-in-out;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- Navigation -->
        <nav class="bg-white/80 backdrop-blur-md border-b border-gray-200 fixed w-full z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex items-center">
                            <div class="w-8 h-8 hero-gradient rounded-lg flex items-center justify-center mr-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                                </svg>
                            </div>
                            <span class="text-xl font-bold text-gray-900">CampusPark</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="/login" class="text-gray-700 hover:text-gray-900 font-medium">Sign In</a>
                        <a href="/register" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-2 rounded-lg font-medium hover:from-blue-600 hover:to-purple-700 transition">
                            Get Started
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="pt-24 pb-16 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center">
                    <h1 class="text-5xl md:text-7xl font-bold text-gray-900 mb-6">
                        Smart Campus
                        <span class="hero-gradient bg-clip-text text-transparent">Parking</span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-10 max-w-3xl mx-auto">
                        A modern parking management system designed for campuses. Reserve spots, manage vehicles, and streamline parking operations with ease.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="/register" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold text-lg hover:from-blue-600 hover:to-purple-700 transition transform hover:-translate-y-1 shadow-lg hover:shadow-xl">
                            Start Free Trial
                        </a>
                        <a href="#features" class="bg-white text-gray-700 px-8 py-4 rounded-xl font-semibold text-lg border-2 border-gray-200 hover:border-gray-300 transition">
                            Learn More
                        </a>
                    </div>
                </div>

                <!-- Dashboard Preview -->
                <div class="mt-20 relative">
                    <div class="absolute inset-0 hero-gradient opacity-10 rounded-3xl"></div>
                    <div class="relative bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl p-8 border border-gray-100">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6">
                                <div class="flex items-center mb-4">
                                    <div class="w-10 h-10 hero-gradient rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm text-gray-500">Available Spots</div>
                                        <div class="text-2xl font-bold text-gray-900">42</div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6">
                                <div class="flex items-center mb-4">
                                    <div class="w-10 h-10 hero-gradient rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm text-gray-500">Active Reservations</div>
                                        <div class="text-2xl font-bold text-gray-900">18</div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-6">
                                <div class="flex items-center mb-4">
                                    <div class="w-10 h-10 hero-gradient rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm text-gray-500">Next Reservation</div>
                                        <div class="text-2xl font-bold text-gray-900">14:30</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Powerful Features</h2>
                    <p class="text-lg text-gray-600">Everything you need for efficient campus parking management</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="feature-card bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-8">
                        <div class="w-12 h-12 hero-gradient rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Real-time Availability</h3>
                        <p class="text-gray-600">See available parking spots in real-time with live updates and occupancy tracking.</p>
                    </div>
                    <div class="feature-card bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-8">
                        <div class="w-12 h-12 hero-gradient rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Easy Reservations</h3>
                        <p class="text-gray-600">Book parking spots in advance with our intuitive reservation system and calendar view.</p>
                    </div>
                    <div class="feature-card bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-8">
                        <div class="w-12 h-12 hero-gradient rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Analytics Dashboard</h3>
                        <p class="text-gray-600">Gain insights with detailed analytics on parking usage, peak hours, and revenue.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="py-20 hero-gradient">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-4xl font-bold text-white mb-6">Ready to transform your campus parking?</h2>
                <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto">Join thousands of campuses already using our platform to manage their parking efficiently.</p>
                <a href="/register" class="bg-white text-gray-900 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-gray-100 transition transform hover:-translate-y-1 shadow-lg hover:shadow-xl">
                    Get Started for Free
                </a>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="flex items-center mb-6 md:mb-0">
                        <div class="w-8 h-8 hero-gradient rounded-lg flex items-center justify-center mr-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold">CampusPark</span>
                    </div>
                    <div class="text-gray-400 text-sm">
                        © {{ date('Y') }} CampusPark. All rights reserved.
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>