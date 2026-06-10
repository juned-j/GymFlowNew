<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('temporary_registrations', function (Blueprint $table) {
            $table->id(); // Int @id @default(autoincrement())
            $table->string('email')->unique(); // String @unique
            $table->string('name'); // String
            $table->string('password'); // String (hashed)
            $table->string('phone')->nullable(); // String? (optional)
            $table->unsignedBigInteger('tenant_id'); // Int
            $table->string('otp'); // String
            $table->timestamp('expires_at'); // DateTime
            $table->timestamp('created_at')->useCurrent(); // DateTime @default(now())
            
            // Optional: If you want to automatically include updated_at, 
            // remove 'created_at' above and use: $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_registrations');
    }
};
