@php
$branding = $tenant?->app_settings['branding'] ?? [];
$logoPath = $branding['logo_url'] ?? $tenant?->logo_url ?? null;
$logo = $logoPath
? asset('storage/' . ltrim($logoPath, '/'))
: null;
$planName = $tenant?->subscription?->plan?->name ?? 'No Active Plan';
@endphp

<div class="fi-header mb-6 w-full">
    <div class="fi-header-content w-full max-w-none">

        <div class="w-full flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4
                    bg-white dark:bg-gray-900
                    border border-gray-200 dark:border-gray-800
                    rounded-2xl px-6 py-4 shadow-sm">

            {{-- LEFT --}}
            <div class="flex items-center gap-4">

                @if($logo)
                <img
                    src="{{ $logo }}"
                    alt="Logo"
                    class="w-14 h-14 rounded-xl object-cover border border-gray-200 dark:border-gray-700">
                @endif

                <div>
                    <div class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ $tenant?->name }}
                    </div>

                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        Dashboard
                    </div>
                </div>

            </div>

            {{-- MIDDLE --}}
            <div class="flex flex-col items-start sm:items-center">
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    Plan
                </div>

                <div class="text-sm font-semibold text-primary-600 dark:text-primary-400">
                    {{ $planName }}
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="flex flex-col items-start sm:items-end">
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
</div>