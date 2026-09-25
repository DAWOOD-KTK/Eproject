<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('username')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'farmer', 'customer'])->default('customer');
            $table->enum('status', ['active', 'pending_approval', 'suspended'])->default('active');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            
            // Farmer specific fields
            $table->string('stall_name')->nullable();
            $table->foreignId('market_id')->nullable()->constrained('markets')->onDelete('set null');
            $table->string('operating_days')->nullable(); // e.g. "Saturday, Sunday"
            $table->string('pickup_windows')->nullable(); // e.g. "08:00 AM - 01:00 PM"
            $table->integer('cutoff_hours')->default(12); // Order cutoff before pickup
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();

            // Customer specific fields
            $table->foreignId('preferred_market_id')->nullable()->constrained('markets')->onDelete('set null');

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
    }
};
