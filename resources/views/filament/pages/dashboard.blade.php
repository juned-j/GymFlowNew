<x-filament-panels::page>

    <x-slot name="header">
        @php
        $tenant = auth()->user()?->ownedTenant;
        $branding = $tenant?->app_settings['branding'] ?? [];
        $logo = $branding['logo_url'] ?? $tenant?->logo_url;
        $planName = $tenant?->subscription?->plan?->name ?? 'No Active Plan';
        @endphp

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow p-6 border border-gray-200 dark:border-gray-800">
            <div class="flex items-center gap-4">
                @if($logo)
                <img
                    src="{{ $logo }}"
                    alt="Logo"
                    class="w-16 h-16 rounded-xl object-cover border">
                @endif

                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $tenant?->name }}
                    </h1>

                    <p class="text-sm text-primary-600 dark:text-primary-400 mt-1 font-medium">
                        Plan: {{ $planName }}
                    </p>

                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Trial Ends:
                        <span class="font-medium">
                            {{ $tenant?->trial_ends_at
                                ? \Carbon\Carbon::parse($tenant->trial_ends_at)->format('d M Y')
                                : 'No Trial'
                            }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

</x-filament-panels::page>