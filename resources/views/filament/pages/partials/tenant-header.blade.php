@php
$branding = $tenant?->app_settings['branding'] ?? [];
$logo = $branding['logo_url'] ?? $tenant?->logo_url;
$planName = $tenant?->subscription?->plan?->name ?? 'No Active Plan';
@endphp

<div class="w-full mb-6">
    <div class="flex items-center justify-between gap-6 
                bg-white dark:bg-gray-900 
                border border-gray-200 dark:border-gray-800 
                rounded-2xl px-6 py-4 shadow-sm">

        {{-- LEFT: Logo + Name --}}
        <div class="flex items-center gap-4 min-w-0">

            @if($logo)
            <img src="{{ $logo }}"
                class="w-12 h-12 rounded-xl object-cover border shrink-0">
            @endif

            <div class="min-w-0">
                <div class="text-lg font-bold text-gray-900 dark:text-white truncate">
                    {{ $tenant?->name }}
                </div>

                <div class="text-xs text-gray-500 dark:text-gray-400">
                    SuperFitness Dashboard
                </div>
            </div>

        </div>

        {{-- MIDDLE: Plan --}}
        <div class="hidden sm:flex flex-col items-center">
            <div class="text-xs text-gray-500 dark:text-gray-400">
                Plan
            </div>
            <div class="text-sm font-semibold text-primary-600 dark:text-primary-400">
                {{ $planName }}
            </div>
        </div>

        {{-- RIGHT: Trial --}}
        <div class="text-right">
            <div class="text-xs text-gray-500 dark:text-gray-400">
                Trial Ends
            </div>

            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                {{ $tenant?->trial_ends_at
                    ? \Carbon\Carbon::parse($tenant->trial_ends_at)->format('d M Y')
                    : 'No Trial'
                }}
            </div>
        </div>

    </div>
</div>