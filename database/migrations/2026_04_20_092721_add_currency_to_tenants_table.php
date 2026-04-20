<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $blueprint) {
            $blueprint->string('currency_symbol', 10)->default('$')->after('currency');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['currency', 'currency_symbol']);
        });
    }
};
