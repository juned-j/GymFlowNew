<x-filament-panels::page>

<div class="max-w-7xl mx-auto px-4 py-6">

    <!-- HEADER -->
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-gray-900">Billing & Subscription</h1>
        <p class="text-gray-600 mt-2">
            Manage your gym subscription plan
        </p>
    </div>

    <!-- CURRENT PLAN -->
    <div class="bg-white shadow rounded-xl p-6 mb-10 border border-gray-200">

        <h2 class="text-xl font-semibold flex items-center gap-2">
            <x-heroicon-o-check-badge class="w-6 h-6 text-green-600" />
            Current Plan
        </h2>

        <p class="text-gray-700 mt-2 text-lg font-medium">
            {{ $tenant->plan->name ?? 'No Plan Assigned' }}
        </p>

        <p class="text-gray-500 mt-1">
            @if(optional($tenant->plan)->monthly_price == 0)
                Free Forever
            @else
                Active Subscription
            @endif
        </p>

        <div class="mt-4">
            <a href="#plans-section"
                class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg">
                Change Plan
            </a>
        </div>

    </div>

    <!-- PLANS SECTION -->
    <h2 id="plans-section" class="text-2xl font-bold text-gray-900 mb-4">
        Available Plans
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        @foreach ($plans as $plan)

        <div class="border rounded-2xl p-6 shadow-sm
            @if(optional($tenant->plan)->id === $plan->id)
                border-primary-500 ring-2 ring-primary-300
            @else
                border-gray-200
            @endif">

            <!-- PLAN NAME -->
            <h3 class="text-xl font-semibold text-gray-900">
                {{ $plan->name }}
            </h3>

            <!-- PRICE -->
            <p class="mt-2 text-3xl font-bold text-gray-800">
                @if($plan->monthly_price == 0)
                    Free
                @else
                    ${{ $plan->monthly_price }}
                    <span class="text-sm text-gray-500">/mo</span>
                @endif
            </p>

            <!-- FEATURES -->
            <ul class="mt-4 space-y-2">
                @foreach ($plan->features ?? [] as $feature)
                    <li class="flex items-center gap-2 text-gray-700 text-sm">
                        <x-heroicon-o-check class="h-5 w-5 text-primary-600" />
                        {{ $feature['label'] ?? '' }}
                    </li>
                @endforeach
            </ul>

            <!-- ACTION BUTTON -->
            <div class="mt-6">

                @if(optional($tenant->plan)->id === $plan->id)

                    <button disabled
                        class="w-full px-4 py-2 bg-gray-200 text-gray-600 rounded-lg">
                        Current Plan
                    </button>

                @else

                    <form method="POST" action="{{ route('billing.subscribe') }}">
                        @csrf

                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <input type="hidden" name="billing_cycle" value="monthly">

                        <button type="submit"
                            class="w-full px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors">
                            Select Plan
                        </button>
                    </form>

                @endif

            </div>

        </div>

        @endforeach

    </div>

</div>

</x-filament-panels::page>