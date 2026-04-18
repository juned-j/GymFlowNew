<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PTBuddy - Professional Personal Training Software</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased selection:bg-indigo-500 selection:text-white">

    <!-- Header -->
    <header class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="text-2xl font-bold text-indigo-600 tracking-tight">
                        PTBuddy
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('features') }}" class="text-gray-600 hover:text-indigo-600 px-3 py-2 text-sm font-medium transition-colors">Features</a>
                    <a href="{{ route('pricing') }}" class="text-gray-600 hover:text-indigo-600 px-3 py-2 text-sm font-medium transition-colors">Pricing</a>
                    <a href="{{ route('contact') }}" class="text-gray-600 hover:text-indigo-600 px-3 py-2 text-sm font-medium transition-colors">Contact</a>
                </nav>

                <!-- Auth actions (Login) -->
                <div class="flex items-center space-x-4">
                    <a href="{{ url('/admin/login') }}" class="text-gray-700 hover:text-indigo-600 font-medium text-sm transition-colors">
                        Sign in
                    </a>
                    <a href="{{ route('pricing') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative bg-white overflow-hidden py-24 sm:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl md:text-6xl mb-6">
                    Manage your clients with <span class="text-indigo-600">absolute ease</span>
                </h1>
                <p class="mt-4 text-xl text-gray-500 font-normal mb-10 leading-relaxed">
                    Stop juggling spreadsheets. PTBuddy is the all-in-one platform for professional trainers to build plans, track progress, and grow their premium fitness business.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ url('/admin/login') }}" class="inline-flex justify-center items-center px-8 py-3.5 border border-transparent text-base font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 shadow-md hover:shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Trainer Login
                    </a>
                    <a href="{{ route('features') }}" class="inline-flex justify-center items-center px-8 py-3.5 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:text-indigo-600 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Explore Features
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Subtle professional background pattern -->
        <div class="absolute top-0 w-full h-full inset-0 z-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(#4f46e5 1px, transparent 1px); background-size: 24px 24px;"></div>
    </section>

    <!-- Features Section -->
    <section class="bg-gray-50 py-24 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Features</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Everything you need to succeed
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Workout Plans</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Create, manage, and assign detailed personalized training programs efficiently.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Track Progress</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Monitor client progress with clear metrics, data insights, and activity logs.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Trainer Dashboard</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Manage your clients, schedules, and complete business overview in one place.
                    </p>
                </div>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <div class="flex items-center">
                <span class="text-xl font-bold text-gray-900">PTBuddy</span>
            </div>
            <div class="text-gray-500 text-sm">
                &copy; {{ date('Y') }} PTBuddy. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>