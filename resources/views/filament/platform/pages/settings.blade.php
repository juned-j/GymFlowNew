<x-filament-panels::page>

<style>
    .bg-yellow-500 { background-color: #eab308; }
    .bg-indigo-500 { background-color: #6366f1; }
    .bg-red-500 { background-color: #ef4444; }

    .p-3 { padding: 0.75rem; }

    .shadow { box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
    .hover\:shadow-md:hover { box-shadow: 0 4px 6px rgba(0,0,0,0.2); }

    .grid { display: grid; }
    .grid-cols-1 { grid-template-columns: repeat(1, 1fr); }
    .gap-4 { gap: 1rem; }

    @media (min-width: 640px) {
        .sm\:grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
    }

    @media (min-width: 768px) {
        .md\:grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
    }

    .rounded-lg { border-radius: 0.5rem; }
    .border { border: 1px solid #d1d5db; }

    .text-center { text-align: center; }

    .font-semibold { font-weight: 600; }

    .text-lg { font-size: 1.125rem; }

    .flex { display: flex; }
    .items-center { align-items: center; }
    .justify-center { justify-content: center; }

    .transition { transition: all 0.2s ease; }

    .space-y-10 > * + * { margin-top: 2.5rem; }

    .card-link {
        display: block;
        text-decoration: none;
    }

    /* ✅ FORCE WHITE (IMPORTANT FIX) */
    .card-link * {
        color: #ffffff !important;
    }
</style>

<div class="space-y-10">

    <h1 class="text-2xl font-bold text-center text-gray-700">
        Master’s
    </h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">

        <!-- Currencies -->
        <a href="{{ route('filament.admin.resources.currencies.index') }}" 
           class="card-link bg-yellow-500 p-3 rounded-lg shadow border hover:shadow-md transition">
            <div class="flex items-center justify-center space-x-2 text-center">
                <i class="fas fa-coins text-xl"></i>
                <span class="text-lg font-semibold">Currencies</span>
            </div>
        </a>

        <!-- Country -->
        <a href="{{ route('filament.admin.resources.countries.index') }}" 
           class="card-link bg-indigo-500 p-3 rounded-lg shadow border hover:shadow-md transition">
            <div class="flex items-center justify-center space-x-2 text-center">
                <i class="fas fa-list text-xl"></i>
                <span class="text-lg font-semibold">Country</span>
            </div>
        </a>

     

    </div>

</div>

</x-filament-panels::page>