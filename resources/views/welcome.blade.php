<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PTBuddy - Personal Training SaaS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: Inter, system-ui, sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased">

    <!-- NAVBAR -->
    <header class="sticky top-0 z-50 bg-white/70 backdrop-blur border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-center justify-between h-20">

                <!-- Logo -->
                <a href="/" class="flex items-center gap-2 font-bold text-xl">
                    <span class="w-9 h-9 rounded-xl bg-indigo-600"></span>
                    PTBuddy
                </a>

                <!-- Actions -->
                <div class="flex items-center gap-3">
                    <a href="{{ url('/admin/login') }}"
                        class="text-sm text-slate-600 hover:text-indigo-600 font-medium">
                        Login
                    </a>

                    <a href="{{ route('register.gym') }}"
                        class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                        Get Started
                    </a>
                </div>

            </div>
        </div>
    </header>

    <!-- HERO -->
    <section class="relative py-28 overflow-hidden">

        <!-- soft background -->
        <div class="absolute inset-0 bg-gradient-to-b from-indigo-50 via-slate-50 to-white"></div>

        <div class="absolute top-[-200px] left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-indigo-500/10 blur-3xl rounded-full"></div>

        <div class="relative max-w-4xl mx-auto text-center px-6">

            <h1 class="text-5xl md:text-6xl font-bold tracking-tight leading-tight">
                Run your fitness business with <span class="text-indigo-600">clarity</span>
            </h1>

            <p class="mt-6 text-lg text-slate-600 max-w-2xl mx-auto">
                PTBuddy helps personal trainers manage clients, build programs, and track progress — all in one simple platform.
            </p>

            <div class="mt-10 flex justify-center gap-4 flex-col sm:flex-row">

                <a href="{{ url('/admin/login') }}"
                    class="px-6 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition shadow-sm">
                    Start Training
                </a>

                <a href="{{ route('features') }}"
                    class="px-6 py-3 rounded-xl border border-slate-200 bg-white text-slate-700 hover:border-indigo-300 hover:text-indigo-600 transition">
                    Explore Features
                </a>

            </div>

        </div>
    </section>

    <!-- FEATURES -->
    <section class="py-24 bg-white border-t border-slate-100">

        <div class="max-w-6xl mx-auto px-6">

            <div class="text-center mb-14">
                <h2 class="text-sm font-semibold text-indigo-600 uppercase tracking-widest">
                    Features
                </h2>
                <p class="text-3xl font-bold mt-2">
                    Everything you need to grow faster
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">

                <!-- Card -->
                <div class="p-8 rounded-2xl border border-slate-100 bg-slate-50 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center mb-6">
                        🏋️
                    </div>
                    <h3 class="font-semibold text-lg">Workout Plans</h3>
                    <p class="text-slate-600 mt-2">
                        Create structured training programs for every client.
                    </p>
                </div>

                <div class="p-8 rounded-2xl border border-slate-100 bg-slate-50 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center mb-6">
                        📊
                    </div>
                    <h3 class="font-semibold text-lg">Progress Tracking</h3>
                    <p class="text-slate-600 mt-2">
                        Track strength, weight, and performance trends.
                    </p>
                </div>

                <div class="p-8 rounded-2xl border border-slate-100 bg-slate-50 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center mb-6">
                        👥
                    </div>
                    <h3 class="font-semibold text-lg">Client Management</h3>
                    <p class="text-slate-600 mt-2">
                        Manage all clients, schedules, and sessions easily.
                    </p>
                </div>

            </div>

        </div>
    </section>

    <!-- FOOTER -->
    <footer class="py-10 border-t border-slate-100 bg-white">
        <div class="max-w-6xl mx-auto px-6 flex justify-between text-sm text-slate-500">
            <span class="font-semibold text-slate-700">PTBuddy</span>
            <span>© {{ date('Y') }} All rights reserved.</span>
        </div>
    </footer>

</body>

</html>