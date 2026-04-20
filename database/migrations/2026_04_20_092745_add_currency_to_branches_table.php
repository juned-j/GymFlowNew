<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $blueprint) {
            // Nullable allows the branch to inherit the tenant's currency
            $blueprint->string('currency', 3)->nullable()->after('tenant_id');
            $blueprint->string('currency_symbol', 10)->nullable()->after('currency');
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['currency', 'currency_symbol']);
        });
    }
};
