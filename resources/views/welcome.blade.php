<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PTBuddy - Personal Training Made Simple</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: system-ui, sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900">

    <!-- Navbar -->
    <header class="flex justify-between items-center p-6 max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold text-green-600">PTBuddy</h1>

        <nav class="space-x-4">
            @auth
                <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-green-600 text-white rounded">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-green-600">
                    Login
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-green-600 text-white rounded">
                        Get Started
                    </a>
                @endif
            @endauth
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="text-center py-20 px-6">
        <h2 class="text-4xl font-bold mb-4">
            Your Personal Trainer, Anytime, Anywhere
        </h2>

        <p class="text-gray-600 max-w-xl mx-auto mb-6">
            PTBuddy helps trainers and clients manage workouts, track progress, and stay consistent.
        </p>

        <a href="{{ route('register') }}"
           class="px-6 py-3 bg-green-600 text-white rounded-lg text-lg">
            Start Training
        </a>
    </section>

    <!-- Features -->
    <section class="max-w-5xl mx-auto grid md:grid-cols-3 gap-6 px-6 pb-20">

        <div class="p-6 bg-white rounded shadow">
            <h3 class="font-semibold text-lg mb-2">Workout Plans</h3>
            <p class="text-gray-600">Create and manage personalized training programs easily.</p>
        </div>

        <div class="p-6 bg-white rounded shadow">
            <h3 class="font-semibold text-lg mb-2">Track Progress</h3>
            <p class="text-gray-600">Monitor client progress with clear insights and logs.</p>
        </div>

        <div class="p-6 bg-white rounded shadow">
            <h3 class="font-semibold text-lg mb-2">Trainer Dashboard</h3>
            <p class="text-gray-600">Manage clients, workouts, and schedules in one place.</p>
        </div>

    </section>

    <!-- Footer -->
    <footer class="text-center text-gray-500 py-6 border-t">
        © {{ date('Y') }} PTBuddy. All rights reserved.
    </footer>

</body>
</html>