<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PTBuddy - Professional Personal Training Software</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: Inter, system-ui, sans-serif;
        }
    </style>
</head>

<body class="bg-[#f8fafc] text-gray-900 antialiased">

<!-- Navbar -->
<header class="bg-white/80 backdrop-blur border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center h-20">

            <!-- Logo -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 shadow-md"></div>
                <span class="text-xl font-bold tracking-tight">PTBuddy</span>
            </div>

            <!-- Nav -->
            <nav class="hidden md:flex gap-8 text-sm font-medium text-gray-600">
                <a href="{{ route('features') }}" class="hover:text-indigo-600 transition">Features</a>
                <a href="{{ route('pricing') }}" class="hover:text-indigo-600 transition">Pricing</a>
                <a href="{{ route('contact') }}" class="hover:text-indigo-600 transition">Contact</a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-4">
                <a href="{{ url('/admin/login') }}"
                   class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition">
                    Sign in
                </a>

                <a href="{{ url('/admin/login') }}"
                   class="px-5 py-2 rounded-xl text-white text-sm font-semibold
                          bg-gradient-to-r from-indigo-600 to-violet-600
                          shadow-md hover:shadow-lg hover:scale-[1.02] transition">
                    Get Started
                </a>
            </div>

        </div>
    </div>
</header>

<!-- Hero -->
<section class="relative overflow-hidden py-28">

    <!-- background glow -->
    <div class="absolute inset-0 bg-gradient-to-b from-indigo-50 via-white to-white"></div>
    <div class="absolute -top-40 left-1/2 w-[600px] h-[600px] -translate-x-1/2 bg-indigo-300/20 blur-3xl rounded-full"></div>

    <div class="relative max-w-4xl mx-auto text-center px-6">

        <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight leading-tight">
            Run Your Fitness Business with <span class="text-indigo-600">PTBuddy</span>
        </h1>

        <p class="mt-6 text-lg text-gray-600 max-w-2xl mx-auto">
            A modern SaaS platform for personal trainers to manage clients,
            build workout plans, and track progress like a pro.
        </p>

        <div class="mt-10 flex justify-center gap-4 flex-col sm:flex-row">

            <a href="{{ url('/admin/login') }}"
               class="px-7 py-3 rounded-xl text-white font-semibold
                      bg-gradient-to-r from-indigo-600 to-violet-600
                      shadow-lg hover:shadow-xl hover:scale-[1.02] transition">
                Start Training
            </a>

            <a href="{{ route('features') }}"
               class="px-7 py-3 rounded-xl border border-gray-200 bg-white
                      hover:border-indigo-300 hover:text-indigo-600 transition">
                Explore Features
            </a>

        </div>

    </div>
</section>

<!-- Features -->
<section class="py-24 bg-white border-t border-gray-100">

    <div class="max-w-6xl mx-auto px-6">

        <div class="text-center mb-16">
            <h2 class="text-sm font-semibold text-indigo-600 uppercase tracking-widest">
                Features
            </h2>
            <p class="text-3xl font-bold mt-2">
                Everything you need to scale your training business
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">

            <!-- Card -->
            <div class="p-8 rounded-2xl bg-gradient-to-b from-white to-gray-50 border border-gray-100 hover:shadow-lg transition">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center mb-6">
                    <span class="text-indigo-600 font-bold">🏋️</span>
                </div>
                <h3 class="font-semibold text-lg mb-2">Workout Plans</h3>
                <p class="text-gray-600">Build structured programs for clients in seconds.</p>
            </div>

            <div class="p-8 rounded-2xl bg-gradient-to-b from-white to-gray-50 border border-gray-100 hover:shadow-lg transition">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center mb-6">
                    <span class="text-indigo-600 font-bold">📊</span>
                </div>
                <h3 class="font-semibold text-lg mb-2">Progress Tracking</h3>
                <p class="text-gray-600">Monitor strength, weight, and performance over time.</p>
            </div>

            <div class="p-8 rounded-2xl bg-gradient-to-b from-white to-gray-50 border border-gray-100 hover:shadow-lg transition">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center mb-6">
                    <span class="text-indigo-600 font-bold">👥</span>
                </div>
                <h3 class="font-semibold text-lg mb-2">Client Management</h3>
                <p class="text-gray-600">Handle all clients, sessions, and schedules in one place.</p>
            </div>

        </div>

    </div>

</section>

<!-- Footer -->
<footer class="py-10 border-t border-gray-100 bg-white">
    <div class="max-w-6xl mx-auto px-6 flex justify-between items-center text-sm text-gray-500">
        <span class="font-semibold text-gray-700">PTBuddy</span>
        <span>© {{ date('Y') }} All rights reserved.</span>
    </div>
</footer>

</body>
</html>