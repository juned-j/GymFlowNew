<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Gym Setup</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: Inter, system-ui, sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased">

<div class="min-h-screen flex items-center justify-center py-16 px-4">

    <div class="w-full max-w-4xl">

        <!-- STEP INDICATOR -->
        <div class="flex items-center justify-center mb-10 gap-6">

            <div class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-semibold">
                    1
                </div>
                <span class="text-sm font-semibold text-indigo-600">Gym</span>
            </div>

            <div class="w-16 h-1 bg-slate-200"></div>

            <div class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center text-sm font-semibold">
                    2
                </div>
                <span class="text-sm text-slate-400">Admin</span>
            </div>

        </div>

        <!-- CARD -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">

            <!-- HEADER -->
            <div class="bg-indigo-600 px-8 py-6">
                <h2 class="text-2xl font-bold text-white">Gym Setup</h2>
                <p class="text-indigo-100 text-sm mt-1">Create your gym profile</p>
            </div>

            <!-- GLOBAL ERROR -->
            @if(session('error'))
                <div class="m-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- FORM -->
            <form method="POST" action="{{ route('register.gym.store') }}" class="p-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Gym Name -->
                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold text-slate-700">
                            Gym Name <span class="text-red-500">*</span>
                        </label>

                        <input type="text" name="name"
                               value="{{ old('name') }}"
                               class="mt-2 w-full px-4 py-3 rounded-xl border 
                               @error('name') border-red-400 @else border-slate-200 @enderror
                               focus:outline-none focus:ring-2 focus:ring-indigo-200"
                               required>

                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="text-sm font-semibold text-slate-700">
                            Email <span class="text-red-500">*</span>
                        </label>

                        <input type="email" name="email"
                               value="{{ old('email') }}"
                               class="mt-2 w-full px-4 py-3 rounded-xl border 
                               @error('email') border-red-400 @else border-slate-200 @enderror
                               focus:outline-none focus:ring-2 focus:ring-indigo-200"
                               required>

                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Phone</label>

                        <input type="text" name="phone"
                               value="{{ old('phone') }}"
                               class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200
                               focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold text-slate-700">Address</label>

                        <input type="text" name="address"
                               value="{{ old('address') }}"
                               class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200
                               focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    </div>

                    <!-- City -->
                    <div>
                        <label class="text-sm font-semibold text-slate-700">City</label>

                        <input type="text" name="city"
                               value="{{ old('city') }}"
                               class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200
                               focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    </div>

                    <!-- Country -->
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Country</label>

                        <input type="text" name="country"
                               value="{{ old('country') }}"
                               class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200
                               focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    </div>

                    <!-- Timezone -->
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Timezone</label>

                        <input type="text" name="timezone"
                               value="{{ old('timezone', 'Asia/Kolkata') }}"
                               class="mt-2 w-full px-4 py-3 rounded-xl border border-slate-200
                               focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    </div>

                </div>

                <!-- BUTTON -->
                <div class="mt-8 flex justify-end">
                    <button type="submit"
                        class="px-6 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                        Continue to Admin Setup →
                    </button>
                </div>

            </form>

        </div>

    </div>
</div>

</body>
</html>