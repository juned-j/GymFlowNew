<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PTBuddy - Smart Personal Training Platform</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: ui-sans-serif, system-ui;
        }
    </style>
</head>

<body class="bg-gradient-to-b from-gray-50 to-white text-gray-900">

<!-- Navbar -->
<header class="max-w-6xl mx-auto flex justify-between items-center p-6">
    
    <div class="flex items-center gap-2">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-green-500 to-emerald-600"></div>
        <h1 class="text-2xl font-bold text-gray-900">PTBuddy</h1>
    </div>

    <nav class="flex items-center gap-6 text-sm font-medium">

        <a href="{{ route('features') }}" class="text-gray-600 hover:text-emerald-600">
            Features
        </a>

        <a href="{{ route('pricing') }}" class="text-gray-600 hover:text-emerald-600">
            Pricing
        </a>

        <a href="{{ route('contact') }}" class="text-gray-600 hover:text-emerald-600">
            Contact
        </a>

        <!-- Login -->
        <a href="{{ url('/admin/login') }}"
           class="px-4 py-2 rounded-lg border border-gray-300 hover:border-emerald-500 hover:text-emerald-600 transition">
            Login
        </a>

        <!-- Primary CTA -->
        <a href="{{ url('/admin/login') }}"
           class="px-4 py-2 rounded-lg bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow hover:shadow-lg transition">
            Get Started
        </a>

    </nav>
</header>

<!-- Hero -->
<section class="text-center px-6 py-24">

    <h2 class="text-5xl font-extrabold leading-tight">
        Train Smarter with <span class="text-emerald-600">PTBuddy</span>
    </h2>

    <p class="mt-6 text-gray-600 max-w-2xl mx-auto text-lg">
        A modern personal training platform for coaches and clients to manage workouts,
        track progress, and achieve fitness goals faster.
    </p>

    <div class="mt-8 flex justify-center gap-4">
        <a href="{{ url('/admin/login') }}"
           class="px-6 py-3 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition">
            Start Training
        </a>

        <a href="{{ route('features') }}"
           class="px-6 py-3 rounded-xl border border-gray-300 hover:border-emerald-500 transition">
            Explore Features
        </a>
    </div>

</section>

<!-- Features -->
<section class="max-w-6xl mx-auto px-6 pb-24 grid md:grid-cols-3 gap-6">

    <div class="p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100">
        <h3 class="font-semibold text-lg mb-2">Workout Management</h3>
        <p class="text-gray-600">Build structured workout plans for clients in seconds.</p>
    </div>

    <div class="p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100">
        <h3 class="font-semibold text-lg mb-2">Progress Tracking</h3>
        <p class="text-gray-600">Track weight, reps, and performance over time.</p>
    </div>

    <div class="p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100">
        <h3 class="font-semibold text-lg mb-2">Trainer Dashboard</h3>
        <p class="text-gray-600">Manage all clients and sessions from one place.</p>
    </div>

</section>

<!-- Footer -->
<footer class="border-t py-8 text-center text-gray-500">
    © {{ date('Y') }} PTBuddy. Built for modern fitness coaching.
</footer>

</body>
</html>