<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} - Plan Selection</title>
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
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 flex justify-center items-center py-12 px-4">
        <div class="w-full max-w-5xl">
            <!-- Progress -->
            <div class="mb-8">
                <div class="flex items-center justify-center gap-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center shadow-lg">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="ml-2 text-sm font-medium text-green-600">Registration</span>
                    </div>
                    <div class="w-16 h-1 bg-indigo-600"></div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-semibold shadow-lg">
                            2
                        </div>
                        <span class="ml-2 text-sm font-medium text-indigo-600">Plan</span>
                    </div>
                </div>
            </div>

            <!-- Header -->
            <div class="text-center mb-10">
                <h1 class="text-4xl font-bold text-gray-900 mb-3">Choose Your Plan</h1>
                <p class="text-gray-600 text-lg">Select the perfect plan for your gym</p>
            </div>

            <form method="POST" action="{{ route('register.plan.store') }}">
                @csrf
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($plans as $index => $plan)
                    <label class="relative group cursor-pointer">
                        <input type="radio" name="plan_id" value="{{ $plan->id }}" class="peer hidden" required>
                        <div class="h-full border-2 border-gray-200 rounded-2xl p-6 transition-all duration-300 hover:border-indigo-300 hover:shadow-xl peer-checked:border-indigo-600 peer-checked:shadow-2xl peer-checked:bg-gradient-to-b peer-checked:from-indigo-50 peer-checked:to-white">
                            <!-- Badge for popular -->
                            @if($index === 1)
                            <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                                <span class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-xs font-semibold px-4 py-1 rounded-full shadow-lg">
                                    Most Popular
                                </span>
                            </div>
                            @endif

                            <!-- Icon -->
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                @if($index === 0)
                                <i class="fas fa-seedling text-2xl text-indigo-600"></i>
                                @elseif($index === 1)
                                <i class="fas fa-rocket text-2xl text-indigo-600"></i>
                                @else
                                <i class="fas fa-crown text-2xl text-indigo-600"></i>
                                @endif
                            </div>

                            <!-- Plan Name -->
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h2>

                            <!-- Price -->
                            <div class="mb-4">
                                <span class="text-4xl font-bold text-indigo-600">₹{{ $plan->monthly_price }}</span>
                                <span class="text-gray-500">/month</span>
                            </div>

                            <!-- Description -->
                            <p class="text-gray-600 mb-4">{{ $plan->description }}</p>

                            <!-- Features -->
                            @if(!empty($plan->features))
                            <ul class="space-y-2 mb-6">
                                @foreach($plan->features as $feature)
                                <li class="flex items-start gap-2 text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                                    <span>{{ is_array($feature) ? ($feature['label'] ?? '') : $feature }}</span>
                                </li>
                                @endforeach
                            </ul>
                            @endif

                            <!-- Selection Indicator -->
                            <div class="flex items-center justify-center gap-2 text-indigo-600 font-semibold opacity-0 peer-checked:opacity-100 transition-opacity">
                                <i class="fas fa-check-circle"></i>
                                <span>Selected</span>
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>

                <!-- Submit Button -->
                <div class="mt-10">
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-2xl px-8 py-4 shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                        <span class="flex items-center justify-center gap-2">
                            Complete Setup
                            <i class="fas fa-arrow-right"></i>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>