<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Billing Plans</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">

<div class="max-w-6xl mx-auto px-4 py-10">

    <!-- HEADER -->
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-gray-900">Choose Your Plan</h1>
        <p class="text-gray-600 mt-2">Select a plan to continue using your gym system</p>
    </div>

    <!-- PLANS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        @foreach ($plans as $plan)

        <div class="bg-white border rounded-xl shadow-sm p-6">

            <!-- PLAN NAME -->
            <h2 class="text-xl font-bold text-gray-900">
                {{ $plan->name }}
            </h2>

            <!-- PRICE -->
            <p class="mt-3 text-3xl font-bold text-gray-800">
                @if($plan->monthly_price == 0)
                    Free
                @else
                    ${{ $plan->monthly_price }}
                    <span class="text-sm text-gray-500">/month</span>
                @endif
            </p>

            <!-- FEATURES -->
            <ul class="mt-4 space-y-2 text-sm text-gray-600">
                @foreach ($plan->features ?? [] as $feature)
                    <li>✔ {{ $feature['label'] ?? '' }}</li>
                @endforeach
            </ul>

            <!-- BUTTON -->
            <form method="POST" action="{{ route('billing.subscribe') }}" class="mt-6">
                @csrf

                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                <input type="hidden" name="billing_cycle" value="monthly">

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg">
                    Select Plan
                </button>
            </form>

        </div>

        @endforeach

    </div>

</div>

</body>
</html>