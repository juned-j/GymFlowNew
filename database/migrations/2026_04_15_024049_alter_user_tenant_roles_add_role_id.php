<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('user_tenant_roles', function (Blueprint $table) {
            // 1️⃣ Add role_id (nullable first to avoid failure)
            $table->foreignId('role_id')
                ->nullable()
                ->after('tenant_id')
                ->constrained('roles')
                ->cascadeOnDelete();
        });

        // 2️⃣ Migrate existing string roles → role_id
        DB::statement("
            UPDATE user_tenant_roles utr
            SET role_id = r.id
            FROM roles r
            WHERE LOWER(utr.role) = LOWER(r.name)
        ");

        Schema::table('user_tenant_roles', function (Blueprint $table) {
            // 3️⃣ Make role_id NOT NULL
            $table->foreignId('role_id')->nullable(false)->change();

            // 4️⃣ Drop old string column
            $table->dropColumn('role');
        });
    }

    public function down(): void
    {
        Schema::table('user_tenant_roles', function (Blueprint $table) {
            // rollback: re-add role string
            $table->string('role')->nullable();
        });

        // restore role names
        DB::statement("
            UPDATE user_tenant_roles utr
            SET role = r.name
            FROM roles r
            WHERE utr.role_id = r.id
        ");

        Schema::table('user_tenant_roles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
        });
    }
};
