@php
$branding = $tenant?->app_settings['branding'] ?? [];
$logo = $branding['logo_url'] ?? $tenant?->logo_url;
$planName = $tenant?->subscription?->plan?->name ?? 'No Active Plan';
@endphp

<div class="fi-header mb-6">
    <div class="fi-header-content">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4
                    bg-white dark:bg-gray-900
                    border border-gray-200 dark:border-gray-800
                    rounded-2xl px-6 py-4 shadow-sm">

            {{-- LEFT --}}
            <div class="flex items-center gap-4">

                @if($logo)
                <img src="{{ $logo }}"
                    class="w-12 h-12 rounded-xl object-cover border">
                @endif

                <div>
                    <div class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ $tenant?->name }}
                    </div>

                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        SuperFitness Dashboard
                    </div>
                </div>

            </div>

            {{-- MIDDLE --}}
            <div class="flex flex-col items-start sm:items-center">
                <div class="text-xs text-gray-500 dark:text-gray-400">Plan</div>
                <div class="text-sm font-semibold text-primary-600 dark:text-primary-400">
                    {{ $planName }}
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="flex flex-col items-start sm:items-end">
                <div class="text-xs text-gray-500 dark:text-gray-400">Trial Ends</div>
                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                    {{ $tenant?->trial_ends_at
                        ? \Carbon\Carbon::parse($tenant->trial_ends_at)->format('d M Y')
                        : 'No Trial'
                    }}
                </div>
            </div>

        </div>

    </div>
</div>