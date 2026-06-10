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
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id(); // SERIAL PRIMARY KEY
            
            // BigInt foreign key to match your Prisma setup
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            
            // OneSignal Subscription ID
            $table->string('onesignal_player_id', 255)->unique();
            
            // 1 = Android, 2 = iOS
            $table->integer('platform_type');
            
            // Automatically handles created_at and updated_at with TIMESTAMP DEFAULT NOW() behavior
            $table->timestamps(); 

            // Explicit index names matching your Prisma schema expectations
            $table->index(['user_id'], 'user_devices_user_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_devices');
    }
};