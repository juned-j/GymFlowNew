<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PTBuddy - Empowering Personal Trainers</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', system-ui, sans-serif; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        .gradient-text {
            background: linear-gradient(135deg, #059669, #14b8a6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .hero-blob {
            position: absolute;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.6;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 relative overflow-x-hidden">

    <!-- Decorative Background -->
    <div class="hero-blob bg-emerald-300 w-96 h-96 rounded-full top-[-100px] left-[-100px]"></div>
    <div class="hero-blob bg-teal-200 w-96 h-96 rounded-full top-[20%] right-[-50px]"></div>
    <div class="hero-blob bg-blue-200 w-80 h-80 rounded-full top-[60%] left-[20%]"></div>

    <!-- Navigation -->
    <header class="fixed top-0 left-0 right-0 z-50 glass-nav">
        <div class="max-w-7xl mx-auto flex justify-between items-center p-5 px-6 lg:px-8">
            <a href="/" class="text-2xl font-extrabold tracking-tight gradient-text">PTBuddy</a>

            <nav class="hidden md:flex space-x-8 items-center font-medium">
                <a href="{{ route('features') }}" class="text-gray-600 hover:text-emerald-600 transition">Features</a>
                <a href="{{ route('pricing') }}" class="text-gray-600 hover:text-emerald-600 transition">Pricing</a>
                <a href="{{ route('contact') }}" class="text-gray-600 hover:text-emerald-600 transition">Contact</a>
            </nav>

            <div class="flex space-x-4 items-center">
                <!-- Explicit Login Link -->
                <a href="{{ url('/admin/login') }}" class="font-semibold text-gray-700 hover:text-emerald-600 transition">
                    Login
                </a>
                <a href="{{ route('pricing') }}" class="hidden sm:inline-block px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                    Get Started
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative pt-40 pb-20 px-6 lg:px-8">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-16">
            <!-- Text Content -->
            <div class="flex-1 text-center lg:text-left z-10">
                <span class="px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-700 text-sm font-semibold tracking-wide uppercase mb-6 inline-block">The #1 Platform for Trainers</span>
                <h2 class="text-5xl lg:text-7xl font-extrabold mb-6 leading-tight tracking-tight">
                    Manage clients <br>
                    <span class="gradient-text">with absolute ease.</span>
                </h2>
                <p class="text-lg text-gray-600 mb-10 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-medium">
                    Elevate your personal training business. Build workout plans, track progression, and manage clients securely – all from one magnificent dashboard.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('pricing') }}" class="px-8 py-4 bg-gray-900 text-white font-bold rounded-xl shadow-2xl hover:shadow-emerald-500/20 hover:-translate-y-1 transition-all duration-300">
                        Start Your Free Trial
                    </a>
                    <a href="{{ url('/admin/login') }}" class="px-8 py-4 bg-white/80 backdrop-blur-sm text-gray-900 font-bold rounded-xl shadow-md border hover:border-emerald-200 border-gray-100 hover:-translate-y-1 transition-all duration-300">
                        Trainer Login
                    </a>
                </div>
            </div>

            <!-- Graphic / Mockup -->
            <div class="flex-1 relative z-10 float-animation w-full max-w-2xl">
                <!-- Our newly generated AI Image Mockup -->
                <div class="rounded-2xl p-3 bg-white/40 backdrop-blur-3xl border border-white/60 shadow-2xl">
                    <img src="{{ asset('images/dashboard-mockup.png') }}" alt="PTBuddy Dashboard Mockup" class="rounded-xl shadow-xl border border-gray-100 w-full object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="max-w-7xl mx-auto px-6 py-24 z-10 relative">
        <div class="text-center mb-16">
            <h3 class="text-3xl font-bold gradient-text mb-4">Everything You Need to Succeed</h3>
            <p class="text-gray-500 text-lg font-medium">Designed by trainers, built for results.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-10">
            <div class="p-8 bg-white/60 backdrop-blur-xl rounded-3xl border border-white/50 shadow-xl hover:-translate-y-2 transition-transform duration-300">
                <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h3 class="font-bold text-xl mb-3">Lightning Fast Plans</h3>
                <p class="text-gray-600 leading-relaxed font-medium">Create and manage personalized training programs in seconds with our AI-assisted builder.</p>
            </div>

            <div class="p-8 bg-white/60 backdrop-blur-xl rounded-3xl border border-white/50 shadow-xl hover:-translate-y-2 transition-transform duration-300">
                <div class="w-14 h-14 bg-teal-100 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
                    <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <h3 class="font-bold text-xl mb-3">Track Progress</h3>
                <p class="text-gray-600 leading-relaxed font-medium">Gain absolute visual clarity on client progress with embedded charts and dynamic logs.</p>
            </div>

            <div class="p-8 bg-white/60 backdrop-blur-xl rounded-3xl border border-white/50 shadow-xl hover:-translate-y-2 transition-transform duration-300">
                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <h3 class="font-bold text-xl mb-3">Client Management</h3>
                <p class="text-gray-600 leading-relaxed font-medium">A centralized hub for your business. Chat, manage schedule, and invoice automatically.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="mt-20 border-t border-gray-200/60 bg-white/50 backdrop-blur-lg">
        <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col md:flex-row items-center justify-between">
            <div class="text-2xl font-extrabold gradient-text mb-4 md:mb-0">PTBuddy</div>
            <div class="flex space-x-6 text-gray-500 font-medium">
                <a href="#" class="hover:text-emerald-600 transition">Privacy</a>
                <a href="#" class="hover:text-emerald-600 transition">Terms</a>
            </div>
            <div class="text-gray-500 font-medium mt-4 md:mt-0">© {{ date('Y') }} PTBuddy. All rights reserved.</div>
        </div>
    </footer>

</body>
</html>