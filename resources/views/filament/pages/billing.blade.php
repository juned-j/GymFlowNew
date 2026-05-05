<x-filament-panels::page>
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900">Billing & Subscription</h1>
            <p class="text-gray-600 mt-2">Manage your gym subscription plan</p>
        </div>

        @php
            $currentPlan = optional($tenant->subscription)->plan;
        @endphp

        <div class="bg-white shadow rounded-xl p-6 mb-10 border border-gray-200">
            <h2 class="text-xl font-semibold flex items-center gap-2">
                <x-heroicon-o-check-badge class="w-6 h-6 text-green-600" />
                Current Plan
            </h2>
            <p class="text-gray-700 mt-2 text-lg font-medium">
                {{ $currentPlan->name ?? 'No Plan Assigned' }}
            </p>
            <div class="mt-4">
                <a href="#plans-section" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg">
                    Change Plan
                </a>
            </div>
        </div>

        <h2 id="plans-section" class="text-2xl font-bold text-gray-900 mb-4">Available Plans</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse ($plans as $plan)
                @php
                    $isCurrent = optional($currentPlan)->id === $plan->id;
                    
                    // FAIL-SAFE: If casting fails, decode manually.
                    $features = $plan->features;
                    if (is_string($features)) {
                        $features = json_decode($features, true);
                    }
                    $features = is_array($features) ? $features : [];
                @endphp

                <div class="border rounded-2xl p-6 shadow-sm {{ $isCurrent ? 'border-primary-500 ring-2 ring-primary-300' : 'border-gray-200' }}">
                    <h3 class="text-xl font-semibold text-gray-900">{{ $plan->name }}</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-800">
                        ₹{{ number_format($plan->price, 2) }}
                    </p>

                    <ul class="mt-4 space-y-2 text-sm text-gray-600">
                        @foreach ($features as $feature)
                            <li>✔ {{ is_array($feature) ? ($feature['label'] ?? '') : $feature }}</li>
                        @endforeach
                    </ul>

                    <div class="mt-6">
                        @if($isCurrent)
                            <button disabled class="w-full px-4 py-2 bg-gray-200 text-gray-600 rounded-lg">Current Plan</button>
                        @else
                            <form method="POST" action="{{ route('billing.subscribe') }}">
                                @csrf
                                <input type="hidden" name="saas_plan_id" value="{{ $plan->id }}">
                                <button type="submit" class="w-full px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors">
                                    Select Plan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-500">No plans available</p>
            @endforelse
        </div>
    </div>
</x-filament-panels::page>