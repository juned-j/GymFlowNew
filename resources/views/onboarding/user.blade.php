<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Admin Setup</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        body {
            font-family: "Inter", sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 flex justify-center items-center py-10 px-4">
        <div class="w-full max-w-3xl">
            <!-- Progress (unchanged) -->
            <div class="mb-6">
                <div class="flex items-center justify-center gap-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center shadow-lg">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="ml-2 text-sm font-medium text-green-600">Gym</span>
                    </div>
                    <div class="w-16 h-1 bg-indigo-600"></div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-semibold shadow-lg">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="ml-2 text-sm font-medium text-indigo-600">Admin</span>
                    </div>
                </div>
            </div>
            <!-- Card -->
            <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-8">
                    <div class="flex items-center justify-center gap-3 mb-2">
                        <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                            <i class="fas fa-user-shield text-white text-xl"></i>
                        </div>
                        <h2 class="text-3xl font-bold text-white">Create Admin Account</h2>
                    </div>
                    <p class="text-indigo-100 text-center">Set your login credentials</p>
                </div>
                <!-- ✅ VALIDATION (SAME STYLE CLEANED) -->
                <div class="px-8 pt-6">
                    @if(session('error'))
                    <div class="mb-5 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-r-lg shadow-sm">
                        {{ session('error') }}
                    </div>
                    @endif
                    @if ($errors->any())
                    <div class="mb-5 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm">
                        <ul class="text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                <!-- Form -->
                <form method="POST" action="{{ route('register.user.store') }}" class="px-8 pb-6">
                    @csrf
                    <div class="space-y-5 mt-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user text-indigo-500 mr-2"></i>Full Name *
                            </label>
                            <input name="name" type="text" value="{{ old('name') }}"
                                class="w-full px-4 py-3 border-2 {{ $errors->has('name') ? 'border-red-500' : 'border-gray-200' }} rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                                placeholder="John Doe"
                                required>
                            @error('name')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-envelope text-indigo-500 mr-2"></i>Email *
                            </label>
                            <input name="email" type="email" value="{{ old('email') }}"
                                class="w-full px-4 py-3 border-2 {{ $errors->has('email') ? 'border-red-500' : 'border-gray-200' }} rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                                placeholder="admin@example.com"
                                required>

                            @error('email')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-lock text-indigo-500 mr-2"></i>Password *
                            </label>
                            <input name="password" type="password"
                                class="w-full px-4 py-3 border-2 {{ $errors->has('password') ? 'border-red-500' : 'border-gray-200' }} rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                                placeholder="••••••••"
                                required>
                            <p class="text-xs text-gray-500 mt-1">
                                Minimum 8 characters, uppercase, lowercase, and numbers
                            </p>
                            @error('password')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Confirm -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-lock text-indigo-500 mr-2"></i>Confirm Password *
                            </label>

                            <input name="password_confirmation" type="password"
                                class="w-full px-4 py-3 border-2 {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-gray-200' }} rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                                placeholder="••••••••"
                                required>

                            @error('password_confirmation')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-between items-center mt-6">

                        <a href="{{ route('register.gym') }}"
                            class="px-6 py-3 bg-gray-100 rounded-xl hover:bg-gray-200 transition flex items-center gap-2">
                            <i class="fas fa-arrow-left"></i>
                            Back
                        </a>

                        <button type="submit"
                            class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3 rounded-xl font-semibold shadow hover:shadow-xl hover:scale-105 transition flex items-center gap-2">
                            Create Account
                            <i class="fas fa-arrow-right"></i>
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</body>

</html>